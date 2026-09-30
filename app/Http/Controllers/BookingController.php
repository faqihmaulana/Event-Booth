<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Booth;
use App\Services\MidtransService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class BookingController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Helper method to check if user is admin
     */
    private function isUserAdmin()
    {
        $user = Auth::user();
        return $user->hasRole('admin') ?? 
               $user->is_admin ?? 
               $user->role === 'admin' ?? false;
    }

    /**
     * Helper method to check authorization for booking
     */
    private function checkBookingAuthorization(Booking $booking, $returnJson = true)
    {
        if (!$this->isUserAdmin() && 
            Schema::hasColumn('bookings', 'user_id') && 
            $booking->getAttribute('user_id') !== Auth::id()) {
            
            if ($returnJson) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            abort(403, 'Unauthorized');
        }
        return null;
    }

    /**
     * Helper method to handle Midtrans transaction cancellation
     */
    private function cancelMidtransTransaction(Booking $booking)
    {
        if ($booking->getAttribute('order_id') && $booking->getAttribute('payment_status') === 'unpaid') {
            try {
                $this->midtransService->cancelTransaction($booking->getAttribute('order_id'));
                $booking->update(['payment_status' => 'cancelled']);
            } catch (\Exception $e) {
                Log::warning('Failed to cancel Midtrans transaction: ' . $e->getMessage());
            }
        }
    }

    /**
     * Auto-cancel expired bookings based on payment_expired_at
     */
    public function autoExpireBookings()
    {
        try {
            $expiredBookings = Booking::where('payment_status', 'unpaid')
                ->where('status', '!=', 'cancelled')
                ->where('payment_expired_at', '<', now())
                ->get();

            foreach ($expiredBookings as $booking) {
                $this->cancelExpiredBooking($booking);
            }

            return response()->json([
                'success' => true,
                'message' => "Auto-expired {$expiredBookings->count()} bookings"
            ]);

        } catch (\Exception $e) {
            Log::error('Auto Expire Bookings Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error auto-expiring bookings'], 500);
        }
    }

    /**
     * Auto-cancel individual booking
     */
    public function autoCancelBooking(Request $request, $bookingId)
    {
        try {
            $booking = Booking::findOrFail($bookingId);
            
            // Check if booking is eligible for auto-cancel
            if ($booking->getAttribute('payment_status') !== 'unpaid' || $booking->getAttribute('status') === 'cancelled') {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking tidak dapat dibatalkan secara otomatis'
                ]);
            }

            // Check if booking has expired
            $expiredHours = $request->input('expired_hours', 0);
            $isExpired = $booking->getAttribute('payment_expired_at') && now()->gt($booking->getAttribute('payment_expired_at'));
            
            if (!$isExpired && $expiredHours < 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking belum mencapai waktu expired'
                ]);
            }

            $this->cancelExpiredBooking($booking);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibatalkan secara otomatis'
            ]);

        } catch (\Exception $e) {
            Log::error('Auto Cancel Booking Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error auto-canceling booking'], 500);
        }
    }

    /**
     * Cancel expired booking
     */
    private function cancelExpiredBooking(Booking $booking)
    {
        try {
            // Update booking status
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => 'expired'
            ]);

            // Free up the booth
            if ($booking->booth) {
                $booking->booth->update(['status' => 'available']);
            }

            // Cancel Midtrans transaction if exists
            if ($booking->getAttribute('order_id')) {
                try {
                    $this->midtransService->cancelTransaction($booking->getAttribute('order_id'));
                } catch (\Exception $e) {
                    Log::warning('Failed to cancel Midtrans transaction for booking ' . $booking->getAttribute('id') . ': ' . $e->getMessage());
                }
            }

            Log::info('Booking auto-cancelled: ' . $booking->getAttribute('id'));

        } catch (\Exception $e) {
            Log::error('Cancel Expired Booking Error: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Menyimpan data booking baru
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false, 'message' => 'Silakan login terlebih dahulu'], 401);
        }

        $validator = Validator::make($request->all(), [
            'booth_id' => 'required|exists:booths,id',
            'company_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        // Load booth dengan relasi event
        $booth = Booth::with('event')->findOrFail($request->input('booth_id'));
        if ($booth->getAttribute('status') !== 'available') {
            return response()->json(['success' => false, 'message' => 'Booth tidak tersedia untuk booking'], 422);
        }

        try {
            $bookingData = [
                'booth_id' => $request->input('booth_id'),
                'company_name' => $request->input('company_name'),
                'contact_person' => $request->input('contact_person'),
                'phone' => $request->input('phone'),
                'email' => $request->input('email'),
                'description' => $request->input('description'),
                'total_price' => $booth->getAttribute('price'),
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_expired_at' => now()->addHours(6) // Set auto-expire time
            ];

            if (Schema::hasColumn('bookings', 'user_id')) {
                $bookingData['user_id'] = Auth::id();
            }

            $booking = Booking::create($bookingData);
            $booth->update(['status' => 'booked']);
            $paymentData = $this->midtransService->createTransaction($booking);

            // Prepare event information for response
            $eventInfo = 'Event tidak ditemukan';
            if ($booth->event) {
                $eventInfo = $booth->event->main_title ?? $booth->event->name ?? $booth->event->title ?? 'Event #' . $booth->event->id;
            }

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat, silakan lakukan payment dalam 6 jam',
                'event' => $eventInfo,
                'booking' => $booking->load('booth.event'),
                'payment_data' => $paymentData
            ]);

        } catch (\Exception $e) {
            Log::error('Booking Creation Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal membuat booking: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menampilkan detail booking
     */
    public function show(Booking $booking)
    {
        $authCheck = $this->checkBookingAuthorization($booking, false);
        if ($authCheck) return $authCheck;

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Mengupdate status booking
     */
    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate(['status' => 'required|in:pending,confirmed,cancelled']);
        
        $authCheck = $this->checkBookingAuthorization($booking);
        if ($authCheck) return $authCheck;

        try {
            $booking->update(['status' => $request->input('status')]);

            if ($request->input('status') == 'cancelled') {
                $booking->booth?->update(['status' => 'available']);
                $this->cancelMidtransTransaction($booking);
            }

            return response()->json(['success' => true, 'message' => 'Status booking berhasil diperbarui']);

        } catch (\Exception $e) {
            Log::error('Update Status Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal memperbarui status: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Menghapus booking
     */
    public function destroy(Booking $booking)
    {
        $authCheck = $this->checkBookingAuthorization($booking);
        if ($authCheck) return $authCheck;

        try {
            $booking->booth?->update(['status' => 'available']);
            $this->cancelMidtransTransaction($booking);
            $booking->delete();

            return response()->json(['success' => true, 'message' => 'Booking berhasil dihapus']);

        } catch (\Exception $e) {
            Log::error('Delete Booking Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal menghapus booking: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Check payment status with auto-expiry check - FIXED VERSION
     */
    public function checkPaymentStatus(Request $request, $bookingId = null)
    {
        try {
            $id = $bookingId ?? $request->route('booking');
            if (!$id) {
                return response()->json(['success' => false, 'message' => 'Booking ID tidak ditemukan'], 400);
            }

            $booking = Booking::with('booth.event')->find($id);
            if (!$booking) {
                return response()->json(['success' => false, 'message' => 'Booking tidak ditemukan'], 404);
            }

            if (!Auth::check()) {
                return response()->json(['success' => false, 'message' => 'User tidak terautentikasi'], 401);
            }

            $authCheck = $this->checkBookingAuthorization($booking);
            if ($authCheck) return $authCheck;

            $currentPaymentStatus = $booking->getAttribute('payment_status') ?? 'unpaid';
            $hasStatusChanged = false;
            $midtransStatus = null;
            $midtransError = null;

            // First check if booking has expired locally (only if payment is still unpaid)
            if ($booking->getAttribute('payment_expired_at') && 
                now()->gt($booking->getAttribute('payment_expired_at')) && 
                $currentPaymentStatus === 'unpaid' && 
                $booking->getAttribute('status') !== 'cancelled') {
                
                // Before marking as expired locally, check Midtrans status first
                if ($booking->getAttribute('order_id')) {
                    try {
                        $midtransStatus = $this->midtransService->checkTransactionStatus($booking->getAttribute('order_id'));
                        
                        if ($midtransStatus && isset($midtransStatus['transaction_status'])) {
                            $midtransTransactionStatus = strtolower($midtransStatus['transaction_status']);
                            
                            // If Midtrans shows payment is successful, update our status
                            if (in_array($midtransTransactionStatus, ['settlement', 'capture'])) {
                                $newPaymentStatus = $this->mapMidtransStatus(
                                    $midtransStatus['transaction_status'], 
                                    $midtransStatus['fraud_status'] ?? null
                                );
                                
                                $this->updatePaymentStatus($booking, $newPaymentStatus, $midtransStatus);
                                $hasStatusChanged = true;
                                
                                Log::info('Payment status updated from Midtrans check', [
                                    'booking_id' => $booking->getAttribute('id'),
                                    'old_status' => $currentPaymentStatus,
                                    'new_status' => $newPaymentStatus,
                                    'midtrans_status' => $midtransTransactionStatus
                                ]);
                            } else {
                                // If Midtrans confirms no payment, then expire locally
                                $this->cancelExpiredBooking($booking);
                                $hasStatusChanged = true;
                            }
                        } else {
                            // Cannot check Midtrans status, expire locally
                            $this->cancelExpiredBooking($booking);
                            $hasStatusChanged = true;
                        }
                    } catch (\Exception $e) {
                        $midtransError = $e->getMessage();
                        Log::warning('Failed to check Midtrans status before expiring', [
                            'booking_id' => $booking->getAttribute('id'),
                            'error' => $e->getMessage()
                        ]);
                        
                        // If we can't check Midtrans, expire locally as fallback
                        $this->cancelExpiredBooking($booking);
                        $hasStatusChanged = true;
                    }
                } else {
                    // No order_id, safe to expire
                    $this->cancelExpiredBooking($booking);
                    $hasStatusChanged = true;
                }
            }

            // If not expired, check Midtrans for status updates
            if (!$hasStatusChanged && $booking->getAttribute('order_id')) {
                try {
                    if (!$midtransStatus) {
                        $midtransStatus = $this->midtransService->checkTransactionStatus($booking->getAttribute('order_id'));
                    }

                    if ($midtransStatus && isset($midtransStatus['transaction_status'])) {
                        $newPaymentStatus = $this->mapMidtransStatus(
                            $midtransStatus['transaction_status'], 
                            $midtransStatus['fraud_status'] ?? null
                        );

                        if ($newPaymentStatus !== $currentPaymentStatus) {
                            $this->updatePaymentStatus($booking, $newPaymentStatus, $midtransStatus);
                            $hasStatusChanged = true;
                            
                            Log::info('Payment status synchronized with Midtrans', [
                                'booking_id' => $booking->getAttribute('id'),
                                'old_status' => $currentPaymentStatus,
                                'new_status' => $newPaymentStatus,
                                'midtrans_status' => $midtransStatus['transaction_status']
                            ]);
                        }
                    }

                } catch (\Exception $e) {
                    $midtransError = $e->getMessage();
                    Log::error('Midtrans Status Check Error: ' . $e->getMessage(), [
                        'booking_id' => $booking->getAttribute('id'),
                        'order_id' => $booking->getAttribute('order_id')
                    ]);
                }
            }

            // Refresh booking data after potential updates
            $booking->refresh();
            $finalPaymentStatus = $booking->getAttribute('payment_status') ?? 'unpaid';

            $responseData = [
                'success' => true,
                'payment_status' => $finalPaymentStatus,
                'booking_status' => $booking->getAttribute('status') ?? 'pending',
                'booking_id' => $booking->getAttribute('id'),
                'message' => $hasStatusChanged
                    ? 'Status pembayaran telah diperbarui'
                    : 'Status pembayaran tidak berubah'
            ];

            // Add additional info for debugging
            if ($midtransStatus) {
                $responseData['midtrans_status'] = $midtransStatus;
            }
            if ($midtransError) {
                $responseData['midtrans_error'] = 'Gagal mengecek status dari payment gateway';
            }

            return response()->json($responseData);

        } catch (\Exception $e) {
            Log::error('Check Payment Status Error: ' . $e->getMessage(), [
                'booking_id' => $bookingId,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan saat mengecek status pembayaran'], 500);
        }
    }

    /**
     * Update payment status and related data - IMPROVED VERSION
     */
    private function updatePaymentStatus(Booking $booking, string $newPaymentStatus, array $midtransStatus)
    {
        $updateData = [
            'payment_status' => $newPaymentStatus,
            'payment_method' => $midtransStatus['payment_type'] ?? null,
            'transaction_id' => $midtransStatus['transaction_id'] ?? null,
            'payment_response' => json_encode($midtransStatus)
        ];

        if ($newPaymentStatus === 'paid' && !$booking->getAttribute('paid_at')) {
            $updateData['paid_at'] = now();
        }

        $booking->update($updateData);
        $this->updateBookingAndBoothStatus($booking, $newPaymentStatus);
    }

    /**
     * Update booking and booth status based on payment status - IMPROVED VERSION
     */
    private function updateBookingAndBoothStatus(Booking $booking, string $paymentStatus)
    {
        try {
            if ($paymentStatus === 'paid') {
                // Payment successful
                if ($booking->booth) {
                    $booking->booth->update(['status' => 'booked']);
                }
                if ($booking->getAttribute('status') === 'pending') {
                    $booking->update(['status' => 'confirmed']);
                }
            } elseif (in_array($paymentStatus, ['failed', 'expired', 'cancelled', 'deny'])) {
                // Payment failed/expired/cancelled
                if ($booking->booth) {
                    $booking->booth->update(['status' => 'available']);
                }
                if ($booking->getAttribute('status') !== 'cancelled') {
                    $booking->update(['status' => 'cancelled']);
                }
                // Clear snap token so user needs to create new payment
                $booking->update(['snap_token' => null]);
            }
        } catch (\Exception $e) {
            Log::error('Update Booking/Booth Status Error: ' . $e->getMessage(), [
                'booking_id' => $booking->getAttribute('id'),
                'payment_status' => $paymentStatus
            ]);
        }
    }

    /**
     * Map Midtrans transaction status to our payment status - ALIGNED WITH MIDTRANSSERVICE
     */
    private function mapMidtransStatus($transactionStatus, $fraudStatus = null)
    {
        if (!is_string($transactionStatus)) return 'unpaid';

        return match (strtolower($transactionStatus)) {
            'capture' => ($fraudStatus === 'challenge') ? 'pending' : 'paid',
            'settlement' => 'paid',
            'pending' => 'pending', // Changed from 'unpaid' to match MidtransService
            'deny' => 'deny',
            'cancel' => 'cancel',
            'expire' => 'expire',
            'failure' => 'failed',
            default => 'unpaid'
        };
    }

    /**
     * Handle Midtrans notification webhook with auto-update - ENHANCED VERSION
     */
    public function handleMidtransNotification(Request $request)
    {
        try {
            Log::info('Midtrans Notification Received', $request->all());
            
            // Let MidtransService handle the notification processing
            $result = $this->midtransService->handleNotification($request->all());
            
            if ($result) {
                Log::info('Midtrans notification processed successfully');
                return response('OK', 200);
            } else {
                Log::error('Failed to process Midtrans notification');
                return response('FAILED', 400);
            }

        } catch (\Exception $e) {
            Log::error('Midtrans Notification Handler Error: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'trace' => $e->getTraceAsString()
            ]);
            return response('ERROR', 500);
        }
    }

    /**
     * Menampilkan booking milik user yang login
     */
    public function myBookings()
    {
        $bookings = Booking::with('booth.event')
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('tenant.my-bookings', compact('bookings'));
    }

    /**
     * Menampilkan pesanan hanya milik user yang login
     */
    public function pesananIndex(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu');
        }

        $query = Booking::with('booth.event');

        if (!$this->isUserAdmin() && Schema::hasColumn('bookings', 'user_id')) {
            $query->where('user_id', Auth::id());
        }

        $query->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('company_name', 'like', "%{$search}%")
                  ->orWhere('contact_person', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->input('payment_status'));
        }

        $bookings = $query->paginate(10);
        return view('pages.pesanan', compact('bookings'));
    }

    /**
     * Process payment for a booking - ENHANCED VERSION
     */
    public function processPayment(Booking $booking)
    {
        $authCheck = $this->checkBookingAuthorization($booking);
        if ($authCheck) return $authCheck;

        if ($booking->getAttribute('payment_status') === 'paid') {
            return response()->json(['success' => false, 'message' => 'Booking sudah dibayar'], 400);
        }

        // Check if booking has expired
        if ($booking->getAttribute('payment_expired_at') && now()->gt($booking->getAttribute('payment_expired_at'))) {
            // But first check Midtrans status before marking as expired
            if ($booking->getAttribute('order_id')) {
                try {
                    $midtransStatus = $this->midtransService->checkTransactionStatus($booking->getAttribute('order_id'));
                    if ($midtransStatus && isset($midtransStatus['transaction_status'])) {
                        $transactionStatus = strtolower($midtransStatus['transaction_status']);
                        
                        if (in_array($transactionStatus, ['settlement', 'capture'])) {
                            // Payment was actually successful, update status
                            $newPaymentStatus = $this->mapMidtransStatus(
                                $midtransStatus['transaction_status'], 
                                $midtransStatus['fraud_status'] ?? null
                            );
                            $this->updatePaymentStatus($booking, $newPaymentStatus, $midtransStatus);
                            $booking->refresh();
                            
                            return response()->json([
                                'success' => true,
                                'message' => 'Pembayaran telah berhasil',
                                'payment_status' => $booking->getAttribute('payment_status')
                            ]);
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to check Midtrans status before processing payment: ' . $e->getMessage());
                }
            }
            
            $this->cancelExpiredBooking($booking);
            return response()->json(['success' => false, 'message' => 'Booking telah expired'], 400);
        }

        try {
            if ($booking->getAttribute('order_id')) {
                $existingStatus = $this->midtransService->checkTransactionStatus($booking->getAttribute('order_id'));

                if ($existingStatus) {
                    $transactionStatus = strtolower($existingStatus['transaction_status'] ?? '');

                    if (in_array($transactionStatus, ['pending', 'settlement', 'capture'])) {
                        if ($booking->getAttribute('snap_token') && $this->isSnapTokenValid($booking)) {
                            return response()->json([
                                'success' => true,
                                'payment_data' => [
                                    'snap_token' => $booking->getAttribute('snap_token'),
                                    'order_id' => $booking->getAttribute('order_id'),
                                    'is_existing' => true
                                ]
                            ]);
                        }
                    }

                    if (in_array($transactionStatus, ['expire', 'deny', 'cancel', 'failure'])) {
                        $this->midtransService->cancelTransaction($booking->getAttribute('order_id'));
                        $booking->update([
                            'order_id' => $this->generateNewOrderId($booking),
                            'snap_token' => null,
                            'payment_status' => 'unpaid'
                        ]);
                    }
                }
            }

            $paymentData = $this->midtransService->createTransaction($booking);
            $booking->update([
                'snap_token' => $paymentData['snap_token'],
                'payment_expired_at' => now()->addHours(6)
            ]);

            return response()->json(['success' => true, 'payment_data' => $paymentData]);

        } catch (\Exception $e) {
            Log::error('Payment Process Error: ' . $e->getMessage(), [
                'booking_id' => $booking->getAttribute('id'),
                'order_id' => $booking->getAttribute('order_id') ?? 'none'
            ]);
            return response()->json(['success' => false, 'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get or create payment token
     */
    public function getPaymentToken(Booking $booking)
    {
        $authCheck = $this->checkBookingAuthorization($booking);
        if ($authCheck) return $authCheck;

        if ($booking->getAttribute('payment_status') === 'paid') {
            return response()->json(['success' => false, 'message' => 'Booking sudah dibayar'], 400);
        }

        // Check if booking has expired
        if ($booking->getAttribute('payment_expired_at') && now()->gt($booking->getAttribute('payment_expired_at'))) {
            $this->cancelExpiredBooking($booking);
            return response()->json(['success' => false, 'message' => 'Booking telah expired'], 400);
        }

        try {
            if ($booking->getAttribute('snap_token') && $this->isSnapTokenValid($booking)) {
                if ($booking->getAttribute('order_id')) {
                    $status = $this->midtransService->checkTransactionStatus($booking->getAttribute('order_id'));
                    if ($status && strtolower($status['transaction_status'] ?? '') === 'pending') {
                        return response()->json([
                            'success' => true,
                            'payment_data' => [
                                'snap_token' => $booking->getAttribute('snap_token'),
                                'order_id' => $booking->getAttribute('order_id'),
                                'is_existing' => true
                            ],
                            'message' => 'Menggunakan token pembayaran yang sudah ada'
                        ]);
                    }
                }
            }

            return $this->processPayment($booking);

        } catch (\Exception $e) {
            Log::error('Get Payment Token Error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Gagal mendapatkan token pembayaran: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Generate new order ID when needed
     */
    private function generateNewOrderId(Booking $booking): string
    {
        return 'BOOTH-' . $booking->getAttribute('id') . '-' . time() . '-' . rand(100, 999);
    }

    /**
     * Check if snap token is still valid
     */
    private function isSnapTokenValid(Booking $booking): bool
    {
        return $booking->getAttribute('payment_expired_at') && now()->lt($booking->getAttribute('payment_expired_at'));
    }

    /**
     * Mendapatkan detail booking untuk modal
     */
    public function getBookingDetail(Booking $booking)
    {
        $authCheck = $this->checkBookingAuthorization($booking);
        if ($authCheck) return $authCheck;

        $booking->load('booth.event');

        return response()->json([
            'success' => true,
            'booking' => [
                'id' => $booking->getAttribute('id'),
                'order_id' => $booking->getAttribute('order_id'),
                'company_name' => $booking->getAttribute('company_name'),
                'contact_person' => $booking->getAttribute('contact_person'),
                'phone' => $booking->getAttribute('phone'),
                'email' => $booking->getAttribute('email'),
                'description' => $booking->getAttribute('description'),
                'total_price' => $booking->getAttribute('total_price'),
                'status' => $booking->getAttribute('status'),
                'payment_status' => $booking->getAttribute('payment_status'),
                'payment_method' => $booking->getAttribute('payment_method'),
                'transaction_id' => $booking->getAttribute('transaction_id'),
                'paid_at' => $booking->getAttribute('paid_at'),
                'payment_expired_at' => $booking->getAttribute('payment_expired_at'),
                'created_at' => $booking->getAttribute('created_at'),
                'booth' => $booking->booth ? [
                    'booth_id' => $booking->booth->getAttribute('booth_id'),
                    'booth_name' => $booking->booth->getAttribute('booth_name') ?? $booking->booth->getAttribute('name'),
                    'section' => $booking->booth->getAttribute('section'),
                    'price' => $booking->booth->getAttribute('price'),
                    'width' => $booking->booth->getAttribute('width'),
                    'height' => $booking->booth->getAttribute('height'),
                    'description' => $booking->booth->getAttribute('description'),
                    'event' => $booking->booth->event ? [
                        'id' => $booking->booth->event->id,
                        'main_title' => $booking->booth->event->main_title ?? $booking->booth->event->name ?? $booking->booth->event->title,
                        'start_date' => $booking->booth->event->start_date,
                        'end_date' => $booking->booth->event->end_date,
                        'category' => $booking->booth->event->category
                    ] : null
                ] : null
            ]
        ]);
    }

    /**
     * Cetak pdf
     */
    public function generateInvoice($id)
    {
        $booking = Booking::with('booth.event')->findOrFail($id);
        
        if ($booking->getAttribute('payment_status') !== 'paid') {
            return redirect()->back()->with('error', 'Invoice hanya tersedia untuk pesanan yang sudah dibayar.');
        }
        
        $pdf = Pdf::loadView('invoice.booking', compact('booking'));
        return $pdf->download('invoice-' . $booking->getAttribute('order_id') . '.pdf');
    }
}