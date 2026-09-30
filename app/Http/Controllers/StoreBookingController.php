<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Booth;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;

class StoreBookingController extends Controller
{
    public function __construct(private MidtransService $midtransService)
    {
    }

    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'booth_id' => ['required', 'integer', 'exists:booths,id'],
            'company_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
        ]);

        $booking = null;
        $booth = null;

        try {
            $booking = DB::transaction(function () use ($validated, $request, &$booth) {
                $booth = Booth::with('event')
                    ->lockForUpdate()
                    ->find($validated['booth_id']);

                if (!$booth || $booth->status !== 'available') {
                    throw ValidationException::withMessages([
                        'booth_id' => 'Booth sedang tidak tersedia. Silakan pilih booth lain.',
                    ]);
                }

                $bookingData = [
                    'booth_id' => $booth->id,
                    'company_name' => $validated['company_name'],
                    'contact_person' => $validated['contact_person'],
                    'phone' => $validated['phone'],
                    'email' => $validated['email'],
                    'description' => $validated['description'] ?? null,
                    'total_price' => $booth->price,
                    'status' => 'pending',
                    'payment_status' => 'unpaid',
                    'payment_expired_at' => now()->addHour(),
                ];

                if (Schema::hasColumn('bookings', 'user_id')) {
                    $bookingData['user_id'] = $request->user()->id;
                }

                $booking = Booking::create($bookingData);
                $booth->update(['status' => 'booked']);
                return $booking->load('booth.event');
            });

            $paymentData = $this->midtransService->createTransaction($booking);

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat, silakan lakukan pembayaran dalam 1 jam.',
                'event' => $booth?->event?->main_title
                    ?? $booth?->event?->name
                    ?? $booth?->event?->title
                    ?? ($booth?->event ? 'Event #' . $booth->event->id : 'Event tidak ditemukan'),
                'booking' => $booking->fresh()->load('booth.event'),
                'payment_data' => $paymentData,
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            if ($booking) {
                DB::transaction(function () use ($booking) {
                    $lockedBooking = Booking::lockForUpdate()->find($booking->id);
                    if (!$lockedBooking) return;

                    if ($lockedBooking->payment_status !== 'paid') {
                        $lockedBooking->update(['status' => 'cancelled', 'payment_status' => 'failed']);
                        $lockedBooking->booth()->update(['status' => 'available']);
                    }
                });
            }

            Log::error('Atomic booking creation failed', [
                'user_id' => $request->user()->id,
                'booth_id' => $validated['booth_id'],
                'error' => $e->getMessage(),
            ]);

            return response()->json(['success' => false, 'message' => 'Booking gagal dibuat. Silakan coba lagi.'], 500);
        }
    }
}
