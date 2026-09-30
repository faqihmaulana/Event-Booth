<?php

namespace App\Services;

use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Midtrans\Config;
use Midtrans\Notification;
use Midtrans\Snap;
use Midtrans\Transaction;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    public function getSnapToken(array $params)
    {
        try {
            return Snap::getSnapToken($params);
        } catch (\Throwable $e) {
            Log::error('Midtrans Snap token error', ['error' => $e->getMessage()]);
            throw new \RuntimeException('Gagal membuat token pembayaran. Silakan coba lagi.');
        }
    }

    public function createTransaction(Booking $booking): array
    {
        if ($booking->payment_status === 'paid') {
            throw new \RuntimeException('Booking sudah dibayar.');
        }

        $orderId = $booking->order_id ?: $this->generateOrderId($booking);
        $grossAmount = (int) round((float) $booking->total_price);
        if ($grossAmount < 1) throw new \RuntimeException('Nominal pembayaran tidak valid.');

        $params = [
            'transaction_details' => ['order_id' => $orderId, 'gross_amount' => $grossAmount],
            'customer_details' => [
                'first_name' => $booking->contact_person,
                'email' => $booking->email,
                'phone' => $booking->phone,
            ],
            'item_details' => [[
                'id' => (string) $booking->booth_id,
                'price' => $grossAmount,
                'quantity' => 1,
                'name' => 'Booth ' . ($booking->booth?->booth_name ?? $booking->booth?->name ?? 'Unknown'),
                'category' => 'Booth Rental',
            ]],
            'callbacks' => [
                'finish' => route('payment.finish'),
                'unfinish' => route('payment.unfinish'),
                'error' => route('payment.error'),
            ],
            'expiry' => ['start_time' => now()->format('Y-m-d H:i:s O'), 'unit' => 'hours', 'duration' => 1],
        ];

        try {
            $snapToken = $this->getSnapToken($params);
            $booking->update([
                'order_id' => $orderId,
                'snap_token' => $snapToken,
                'payment_status' => 'unpaid',
                'payment_expired_at' => now()->addHour(),
            ]);
            return ['snap_token' => $snapToken, 'order_id' => $orderId, 'redirect_url' => $this->getPaymentUrl($snapToken)];
        } catch (\Throwable $e) {
            Log::error('Midtrans transaction creation failed', ['booking_id' => $booking->id, 'error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function generateOrderId(Booking $booking): string
    {
        return $booking->generateOrderId();
    }

    public function getPaymentUrl(string $snapToken): string
    {
        $host = Config::$isProduction ? 'https://app.midtrans.com' : 'https://app.sandbox.midtrans.com';
        return $host . '/snap/v2/vtweb/' . rawurlencode($snapToken);
    }

    public function handleNotification($notificationPayload = null): bool
    {
        try {
            $notif = new Notification();
            $data = $notif->getResponse();
            $data = is_object($data) ? json_decode(json_encode($data), true) : (array) $data;

            $orderId = (string) ($data['order_id'] ?? '');
            $transactionStatus = strtolower((string) ($data['transaction_status'] ?? ''));
            $statusCode = (string) ($data['status_code'] ?? '');
            $grossAmount = (string) ($data['gross_amount'] ?? '');
            $signatureKey = (string) ($data['signature_key'] ?? '');

            if ($orderId === '' || $transactionStatus === '' || $statusCode === '' || $grossAmount === '') return false;

            $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));
            if ($signatureKey === '' || !hash_equals($expectedSignature, $signatureKey)) {
                Log::warning('Midtrans notification rejected: invalid signature', ['order_id' => $orderId]);
                return false;
            }

            $booking = Booking::with('booth')->where('order_id', $orderId)->first();
            if (!$booking) return false;

            $expectedAmount = number_format((float) $booking->total_price, 2, '.', '');
            $receivedAmount = number_format((float) $grossAmount, 2, '.', '');
            if ($expectedAmount !== $receivedAmount) {
                Log::warning('Midtrans notification rejected: amount mismatch', ['order_id' => $orderId]);
                return false;
            }

            $paymentStatus = match ($transactionStatus) {
                'capture' => (($data['fraud_status'] ?? null) === 'challenge') ? 'pending' : 'paid',
                'settlement' => 'paid',
                'pending' => 'pending',
                'deny' => 'deny',
                'cancel' => 'cancel',
                'expire' => 'expire',
                'failure' => 'failed',
                default => null,
            };
            if ($paymentStatus === null) return false;

            $update = [
                'transaction_id' => $data['transaction_id'] ?? null,
                'payment_method' => $data['payment_type'] ?? null,
                'payment_response' => $data,
                'payment_status' => $paymentStatus,
            ];

            if ($paymentStatus === 'paid') {
                if ($booking->status === 'cancelled') {
                    Log::warning('Paid notification received for cancelled booking', ['booking_id' => $booking->id]);
                    return true;
                }
                $update['paid_at'] = $booking->paid_at ?: now();
                $update['status'] = 'confirmed';
                $booking->booth?->update(['status' => 'booked']);
            } elseif (in_array($paymentStatus, ['deny', 'cancel', 'expire', 'failed'], true)) {
                $update['status'] = 'cancelled';
                $update['snap_token'] = null;
                $booking->booth?->update(['status' => 'available']);
            }

            $booking->update($update);
            Log::info('Midtrans notification processed', ['booking_id' => $booking->id, 'order_id' => $orderId, 'payment_status' => $paymentStatus]);
            return true;
        } catch (\Throwable $e) {
            Log::error('Midtrans notification error', ['error' => $e->getMessage()]);
            return false;
        }
    }

    public function checkTransactionStatus($orderId)
    {
        try {
            $result = Transaction::status($orderId);
            return is_object($result) ? json_decode(json_encode($result), true) : $result;
        } catch (\Throwable $e) {
            Log::warning('Midtrans status check failed', ['order_id' => $orderId, 'error' => $e->getMessage()]);
            return null;
        }
    }

    public function cancelTransaction($orderId)
    {
        try { return Transaction::cancel($orderId); }
        catch (\Throwable $e) { Log::warning('Midtrans cancel failed', ['order_id' => $orderId]); return null; }
    }

    public function expireTransaction($orderId)
    {
        try { return Transaction::expire($orderId); }
        catch (\Throwable $e) { Log::warning('Midtrans expire failed', ['order_id' => $orderId]); return null; }
    }
}
