<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SecurePaymentController extends Controller
{
    public function __construct(private MidtransService $midtransService)
    {
    }

    private function authorize(Booking $booking): void
    {
        abort_unless(auth()->user()->isAdmin() || (int) $booking->user_id === (int) auth()->id(), 403);
    }

    public function status(Request $request, Booking $booking)
    {
        $this->authorize($booking);
        $booking->load('booth.event');

        if ($booking->payment_status === 'paid') {
            return $this->response($booking);
        }

        if (!$booking->order_id) {
            return $this->response($booking);
        }

        $midtrans = $this->midtransService->checkTransactionStatus($booking->order_id);

        // A temporary Midtrans/API failure must never be treated as an expired payment.
        if (!$midtrans || !isset($midtrans['transaction_status'])) {
            return response()->json([
                'success' => false,
                'message' => 'Status pembayaran belum dapat diverifikasi. Silakan coba lagi.',
                'payment_status' => $booking->payment_status,
                'booking_status' => $booking->status,
            ], 503);
        }

        $status = strtolower($midtrans['transaction_status']);
        $fraud = strtolower((string) ($midtrans['fraud_status'] ?? ''));

        if (in_array($status, ['settlement', 'capture'], true) && ($status !== 'capture' || $fraud !== 'challenge')) {
            DB::transaction(function () use ($booking, $midtrans) {
                $locked = Booking::lockForUpdate()->findOrFail($booking->id);
                if ($locked->payment_status !== 'paid' && $locked->status !== 'cancelled') {
                    $locked->update([
                        'payment_status' => 'paid',
                        'status' => 'confirmed',
                        'transaction_id' => $midtrans['transaction_id'] ?? $locked->transaction_id,
                        'payment_method' => $midtrans['payment_type'] ?? $locked->payment_method,
                        'paid_at' => $locked->paid_at ?: now(),
                        'payment_response' => $midtrans,
                    ]);
                    $locked->booth()->update(['status' => 'booked']);
                }
            });
        } elseif ($status === 'pending') {
            $booking->update([
                'payment_status' => 'pending',
                'payment_response' => $midtrans,
            ]);
        } elseif (in_array($status, ['expire', 'cancel', 'deny', 'failure'], true)) {
            DB::transaction(function () use ($booking, $status, $midtrans) {
                $locked = Booking::lockForUpdate()->findOrFail($booking->id);
                if ($locked->payment_status !== 'paid') {
                    $locked->update([
                        'payment_status' => $status === 'expire' ? 'expired' : ($status === 'failure' ? 'failed' : $status),
                        'status' => 'cancelled',
                        'payment_response' => $midtrans,
                        'snap_token' => null,
                    ]);
                    $locked->booth()->update(['status' => 'available']);
                }
            });
        }

        $booking->refresh();
        return $this->response($booking);
    }

    public function token(Request $request, Booking $booking)
    {
        $this->authorize($booking);

        if ($booking->payment_status === 'paid') {
            return response()->json(['success' => false, 'message' => 'Booking sudah dibayar.'], 409);
        }

        if ($booking->order_id) {
            $status = $this->midtransService->checkTransactionStatus($booking->order_id);
            $transactionStatus = strtolower((string) ($status['transaction_status'] ?? ''));

            if ($transactionStatus === 'pending' && $booking->snap_token) {
                return response()->json([
                    'success' => true,
                    'payment_data' => [
                        'snap_token' => $booking->snap_token,
                        'order_id' => $booking->order_id,
                        'is_existing' => true,
                    ],
                ]);
            }

            if ($transactionStatus === 'settlement' || $transactionStatus === 'capture') {
                return response()->json(['success' => false, 'message' => 'Pembayaran sedang/ telah diproses. Silakan refresh status pembayaran.'], 409);
            }

            if (!in_array($transactionStatus, ['', 'expire', 'cancel', 'deny', 'failure'], true)) {
                return response()->json(['success' => false, 'message' => 'Status transaksi belum memungkinkan pembayaran baru.'], 409);
            }
        }

        if ($booking->payment_expired_at && now()->greaterThan($booking->payment_expired_at)) {
            $booking->update(['status' => 'cancelled', 'payment_status' => 'expired']);
            $booking->booth()->update(['status' => 'available']);
            return response()->json(['success' => false, 'message' => 'Booking telah expired.'], 409);
        }

        try {
            $payment = $this->midtransService->createTransaction($booking->fresh('booth'));
            return response()->json(['success' => true, 'payment_data' => $payment]);
        } catch (\Throwable $e) {
            Log::error('Secure payment token error', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Gagal membuat pembayaran. Silakan coba lagi.'], 500);
        }
    }

    public function process(Request $request, Booking $booking)
    {
        return $this->token($request, $booking);
    }

    private function response(Booking $booking)
    {
        return response()->json([
            'success' => true,
            'payment_status' => $booking->payment_status ?? 'unpaid',
            'booking_status' => $booking->status ?? 'pending',
            'booking_id' => $booking->id,
            'order_id' => $booking->order_id,
        ]);
    }
}
