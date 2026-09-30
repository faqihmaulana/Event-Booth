<?php

namespace App\Http\Controllers;

use App\Services\MidtransService;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $midtransService;

    public function __construct(MidtransService $midtransService)
    {
        $this->midtransService = $midtransService;
    }

    /**
     * Handle Midtrans notification webhook
     */
    public function notification(Request $request)
    {
        try {
            $result = $this->midtransService->handleNotification($request->all());
            
            if ($result) {
                return response('OK', 200);
            } else {
                return response('FAILED', 400);
            }
        } catch (\Exception $e) {
            Log::error('Payment Notification Error: ' . $e->getMessage());
            return response('ERROR', 500);
        }
    }

    /**
     * Payment finish callback
     */
    public function finish(Request $request)
    {
        $orderId = $request->input('order_id');
        $booking = Booking::where('order_id', $orderId)->first();

        if ($booking) {
            // Check latest payment status from Midtrans
            $status = $this->midtransService->checkTransactionStatus($orderId);
            
            return view('payment.finish', compact('booking', 'status'));
        }

        return view('payment.error')->with('message', 'Booking tidak ditemukan');
    }

    /**
     * Payment unfinish callback
     */
    public function unfinish(Request $request)
    {
        $orderId = $request->input('order_id');
        $booking = Booking::where('order_id', $orderId)->first();

        return view('payment.unfinish', compact('booking'));
    }

    /**
     * Payment error callback
     */
    public function error(Request $request)
    {
        $orderId = $request->input('order_id');
        $booking = Booking::where('order_id', $orderId)->first();

        return view('payment.error', compact('booking'));
    }

    /**
     * Show payment page
     */
    public function show($bookingId)
    {
        $booking = Booking::with('booth')->findOrFail($bookingId);

        // Check if booking is already paid
        if ($booking->isPaid()) {
            return redirect()->route('booking.success', $booking->id);
        }

        // Check if payment is expired
        if ($booking->isPaymentExpired()) {
            return redirect()->route('booking.expired', $booking->id);
        }

        return view('payment.show', compact('booking'));
    }

    /**
     * Process payment
     */
    public function process(Request $request, $bookingId)
    {
        $booking = Booking::with('booth')->findOrFail($bookingId);

        // Check if booking is already paid
        if ($booking->isPaid()) {
            return response()->json([
                'success' => false,
                'message' => 'Booking sudah dibayar'
            ], 400);
        }

        // Check if payment is expired
        if ($booking->isPaymentExpired()) {
            return response()->json([
                'success' => false,
                'message' => 'Waktu pembayaran telah habis'
            ], 400);
        }

        try {
            if (!$booking->order_id) {
                // Create new transaction if not exists
                $paymentData = $this->midtransService->createTransaction($booking);
            } else {
                // Use existing snap token
                $paymentData = [
                    'order_id' => $booking->order_id
                ];
            }

            return response()->json([
                'success' => true,
                'payment_data' => $paymentData
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Process Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memproses pembayaran: ' . $e->getMessage()
            ], 500);
        }
    }
}