<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Transaction;
use Midtrans\Notification;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        // Initialize Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;
    }

    /**
     * Create a Snap token for payment - ENHANCED VERSION
     */
    public function getSnapToken(array $params)
    {
        try {
            return Snap::getSnapToken($params);
        } catch (\Exception $e) {
            $errorMessage = $e->getMessage();
            
            // Handle specific error cases
            if (strpos($errorMessage, 'order_id has already been taken') !== false) {
                throw new \Exception('Order ID sudah digunakan. Silakan coba lagi dengan order ID baru.');
            }
            
            Log::error('Get Snap Token Error: ' . $errorMessage, [
                'params' => $params
            ]);
            
            throw new \Exception('Failed to generate payment token: ' . $errorMessage);
        }
    }

    /**
     * Create payment transaction for booking - ENHANCED VERSION
     */
    public function createTransaction(Booking $booking)
    {
        // Generate order ID if not exists or create new one
        $orderId = $booking->order_id ?? $this->generateOrderId($booking);
        
        // Double check if this order_id already exists in Midtrans
        if ($booking->order_id) {
            try {
                $existingTransaction = $this->checkTransactionStatus($booking->order_id);
                if ($existingTransaction) {
                    // Fix: Handle both array and object responses
                    $transactionStatus = is_array($existingTransaction) 
                        ? strtolower($existingTransaction['transaction_status'] ?? '')
                        : strtolower($existingTransaction->transaction_status ?? '');
                    
                    // If transaction exists and is not in final state, we need new order_id
                    if (in_array($transactionStatus, ['pending', 'settlement', 'capture'])) {
                        // Try to use existing transaction if it's still valid
                        if ($transactionStatus === 'pending') {
                            // We can't get snap token from existing transaction, so we need to create new order_id
                            $orderId = $this->generateOrderId($booking);
                        }
                    }
                }
            } catch (\Exception $e) {
                // If we can't check existing transaction, generate new order_id to be safe
                $orderId = $this->generateOrderId($booking);
                Log::warning('Could not check existing transaction, generating new order_id: ' . $e->getMessage());
            }
        }
        
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $booking->total_price,
            ],
            'customer_details' => [
                'first_name' => $booking->contact_person,
                'email' => $booking->email,
                'phone' => $booking->phone,
            ],
            'item_details' => [
                [
                    'id' => $booking->booth_id,
                    'price' => (int) $booking->total_price,
                    'quantity' => 1,
                    'name' => 'Booth ' . ($booking->booth->booth_name ?? $booking->booth->name ?? 'Unknown'),
                    'category' => 'Booth Rental'
                ]
            ],
            'callbacks' => [
                'finish' => route('payment.finish'),
                'unfinish' => route('payment.unfinish'),
                'error' => route('payment.error')
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 1
            ]
        ];

        try {
            $snapToken = $this->getSnapToken($params);
            
            // Update booking with new order ID and expiry
            $booking->update([
                'order_id' => $orderId,
                'payment_expired_at' => now()->addHours(1)
            ]);

            return [
                'snap_token' => $snapToken,
                'order_id' => $orderId,
                'redirect_url' => $this->getPaymentUrl($snapToken)
            ];
            
        } catch (\Exception $e) {
            Log::error('Midtrans Transaction Error: ' . $e->getMessage(), [
                'booking_id' => $booking->id,
                'order_id' => $orderId,
                'params' => $params
            ]);
            
            // If it's order_id conflict, try once more with new order_id
            if (strpos($e->getMessage(), 'order_id has already been taken') !== false) {
                $newOrderId = $this->generateOrderId($booking);
                $params['transaction_details']['order_id'] = $newOrderId;
                
                try {
                    $snapToken = $this->getSnapToken($params);
                    
                    $booking->update([
                        'order_id' => $newOrderId,
                        'payment_expired_at' => now()->addHours(1)
                    ]);

                    return [
                        'snap_token' => $snapToken,
                        'order_id' => $newOrderId,
                        'redirect_url' => $this->getPaymentUrl($snapToken)
                    ];
                    
                } catch (\Exception $retryError) {
                    Log::error('Midtrans Retry Transaction Error: ' . $retryError->getMessage());
                    throw new \Exception('Failed to create payment transaction after retry: ' . $retryError->getMessage());
                }
            }
            
            throw new \Exception('Failed to create payment transaction: ' . $e->getMessage());
        }
    }

    /**
     * Generate unique order ID for booking
     */
    private function generateOrderId(Booking $booking): string
    {
        return 'BOOTH-' . $booking->id . '-' . time() . '-' . rand(1000, 9999);
    }

    /**
     * Get payment URL using Snap token
     */
    public function getPaymentUrl(string $snapToken): string
    {
        return Config::$isProduction 
            ? "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}"
            : "https://app.sandbox.midtrans.com/snap/v2/vtweb/{$snapToken}";
    }

    /**
     * Handle Midtrans payment notification - ENHANCED VERSION
     */
    public function handleNotification($notificationPayload)
    {
        try {
            $notif = new Notification();
            
            // Fix: Get notification data safely
            $notifData = $notif->getResponse();
            
            // Handle both array and object responses
            $transaction = is_array($notifData) ? ($notifData['transaction_status'] ?? null) : ($notif->transaction_status ?? null);
            $type = is_array($notifData) ? ($notifData['payment_type'] ?? null) : ($notif->payment_type ?? null);
            $orderId = is_array($notifData) ? ($notifData['order_id'] ?? null) : ($notif->order_id ?? null);
            $fraud = is_array($notifData) ? ($notifData['fraud_status'] ?? null) : ($notif->fraud_status ?? null);
            $transactionId = is_array($notifData) ? ($notifData['transaction_id'] ?? null) : ($notif->transaction_id ?? null);

            // Validate required fields
            if (!$orderId || !$transaction) {
                Log::error('Missing required notification data', [
                    'order_id' => $orderId,
                    'transaction_status' => $transaction,
                    'notification_data' => $notifData
                ]);
                return false;
            }

            // Find booking by order ID
            $booking = Booking::where('order_id', $orderId)->first();
            
            if (!$booking) {
                Log::error('Booking not found for order ID: ' . $orderId);
                return false;
            }

            // Prepare update data
            $updateData = [
                'transaction_id' => $transactionId,
                'payment_method' => $type,
                'payment_response' => json_encode($notifData)
            ];

            // Handle transaction status
            switch ($transaction) {
                case 'capture':
                    $updateData['payment_status'] = ($type == 'credit_card' && $fraud == 'challenge') 
                        ? 'pending' 
                        : 'paid';
                    break;
                case 'settlement':
                    $updateData['payment_status'] = 'paid';
                    break;
                case 'pending':
                    $updateData['payment_status'] = 'pending';
                    break;
                case 'deny':
                case 'expire':
                case 'cancel':
                    $updateData['payment_status'] = strtolower($transaction);
                    break;
                default:
                    Log::warning('Unknown transaction status: ' . $transaction);
                    return false;
            }

            // If payment is successful
            if ($updateData['payment_status'] === 'paid') {
                $updateData['paid_at'] = now();
                $updateData['status'] = 'confirmed';
                
                // Mark booth as booked
                if ($booking->booth) {
                    $booking->booth->update(['status' => 'booked']);
                }
            } 
            // If payment failed or expired
            elseif (in_array($updateData['payment_status'], ['deny', 'expire', 'cancel'])) {
                // Release booth if payment failed
                if ($booking->booth) {
                    $booking->booth->update(['status' => 'available']);
                }
                $updateData['status'] = 'cancelled';
                $updateData['snap_token'] = null; // Clear snap token
            }

            // Update booking
            $booking->update($updateData);

            Log::info("Payment notification processed for booking {$booking->id}: {$transaction}", [
                'order_id' => $orderId,
                'payment_status' => $updateData['payment_status']
            ]);

            return true;
        } catch (\Exception $e) {
            Log::error('Midtrans Notification Error: ' . $e->getMessage(), [
                'notification_payload' => $notificationPayload,
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Check transaction status - ENHANCED VERSION
     */
    public function checkTransactionStatus($orderId)
    {
        try {
            $result = Transaction::status($orderId);
            
            // Convert to array if it's an object for consistent handling
            $resultArray = is_object($result) ? json_decode(json_encode($result), true) : $result;
            
            // Log the transaction check for debugging
            Log::info('Transaction status checked', [
                'order_id' => $orderId,
                'status' => $resultArray['transaction_status'] ?? 'unknown'
            ]);
            
            return $resultArray;
        } catch (\Exception $e) {
            Log::error('Check Transaction Status Error: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);
            
            // Return null instead of throwing exception to allow graceful handling
            return null;
        }
    }

    /**
     * Cancel transaction - ENHANCED VERSION
     */
    public function cancelTransaction($orderId)
    {
        try {
            $result = Transaction::cancel($orderId);
            
            Log::info('Transaction cancelled', [
                'order_id' => $orderId,
                'result' => $result
            ]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Cancel Transaction Error: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);
            
            // Don't throw exception, just return null
            return null;
        }
    }

    /**
     * Expire transaction (for cleanup purposes)
     */
    public function expireTransaction($orderId)
    {
        try {
            $result = Transaction::expire($orderId);
            
            Log::info('Transaction expired', [
                'order_id' => $orderId,
                'result' => $result
            ]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Expire Transaction Error: ' . $e->getMessage(), [
                'order_id' => $orderId
            ]);
            
            return null;
        }
    }
}