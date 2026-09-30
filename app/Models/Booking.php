<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'booth_id',
        'company_name',
        'contact_person',
        'phone',
        'email',
        'booking_date',
        'description',
        'total_price',
        'status',
        'payment_status',
        'payment_method',
        'transaction_id',
        'order_id',
        'snap_token',
        'payment_response',
        'paid_at',
        'payment_expired_at'
    ];

    protected $attributes = [
        'booking_date' => null,
    ];

    protected $casts = [
        'booking_date' => 'date',
        'total_price' => 'decimal:2',
        'payment_response' => 'array',
        'paid_at' => 'datetime',
        'payment_expired_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        // Set default booking_date to today if not provided
        static::creating(function ($booking) {
            if (empty($booking->booking_date)) {
                $booking->booking_date = now()->format('Y-m-d');
            }
            
            // Generate order_id if not provided
            if (empty($booking->order_id)) {
                $booking->order_id = 'BOOTH-' . date('Ymd') . '-' . time() . '-' . rand(100, 999);
            }
        });

        // Update order_id after creation with actual ID
        static::created(function ($booking) {
            if (strpos($booking->order_id, 'BOOTH-') === 0 && !strpos($booking->order_id, '-' . $booking->id . '-')) {
                $booking->update([
                    'order_id' => 'BOOTH-' . $booking->id . '-' . date('Ymd') . '-' . time()
                ]);
            }
        });
    }

    /**
     * Relasi ke booth
     */
    public function booth()
    {
        return $this->belongsTo(Booth::class);
    }

    /**
     * Relasi ke user (jika ada)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke event melalui booth
     */
    public function event()
    {
        return $this->hasOneThrough(
            Event::class,      // Target model
            Booth::class,      // Intermediate model
            'id',              // Foreign key on intermediate model
            'id',              // Foreign key on target model
            'booth_id',        // Local key on this model
            'event_id'         // Local key on intermediate model
        );
    }

    /**
     * Accessor untuk mendapatkan nama event
     */
    public function getEventNameAttribute()
    {
        if ($this->booth && $this->booth->event) {
            return $this->booth->event->main_title ?? 
                   $this->booth->event->name ?? 
                   $this->booth->event->title ?? 
                   'Event #' . $this->booth->event->id;
        }
        return 'Event tidak ditemukan';
    }

    /**
     * Accessor untuk mendapatkan info event lengkap
     */
    public function getEventInfoAttribute()
    {
        if ($this->booth && $this->booth->event) {
            $event = $this->booth->event;
            return [
                'id' => $event->id,
                'name' => $event->main_title ?? $event->name ?? $event->title ?? 'Event #' . $event->id,
                'start_date' => $event->start_date ?? null,
                'end_date' => $event->end_date ?? null,
                'category' => $event->category ?? null,
                'location' => $event->location ?? null,
            ];
        }
        return null;
    }

    /**
     * Generate unique order ID
     */
    public function generateOrderId()
    {
        return 'BOOTH-' . $this->id . '-' . date('Ymd') . '-' . time();
    }

    /**
     * Check if payment is expired
     */
    public function isPaymentExpired()
    {
        return $this->payment_expired_at && now()->isAfter($this->payment_expired_at);
    }

    /**
     * Check if booking is paid
     */
    public function isPaid()
    {
        return $this->payment_status === 'paid';
    }

    /**
     * Check if booking is confirmed
     */
    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    /**
     * Check if booking is cancelled
     */
    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    /**
     * Get status badge class for UI
     */
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'confirmed' => 'bg-success',
            'cancelled' => 'bg-danger',
            default => 'bg-secondary'
        };
    }

    /**
     * Get payment status badge class for UI
     */
    public function getPaymentStatusBadgeAttribute()
    {
        return match($this->payment_status) {
            'paid' => 'bg-success',
            'unpaid' => 'bg-warning',
            'failed' => 'bg-danger',
            'expired' => 'bg-secondary',
            'cancelled' => 'bg-dark',
            default => 'bg-secondary'
        };
    }

    /**
     * Scope for filtering by payment status
     */
    public function scopeByPaymentStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    /**
     * Scope for expired payments
     */
    public function scopeExpiredPayments($query)
    {
        return $query->where('payment_status', 'unpaid')
                    ->where('payment_expired_at', '<', now());
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('booking_date', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by event
     */
    public function scopeByEvent($query, $eventId)
    {
        return $query->whereHas('booth', function($q) use ($eventId) {
            $q->where('event_id', $eventId);
        });
    }

    /**
     * Scope untuk booking yang perlu diupdate statusnya
     */
    public function scopeNeedsStatusUpdate($query)
    {
        return $query->where('payment_status', 'unpaid')
                    ->where('status', 'pending')
                    ->whereNotNull('order_id');
    }
}