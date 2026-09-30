<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'main_title',
        'main_description', 
        'start_date',
        'end_date',
        'main_image',
        'location',          // Ditambahkan sesuai migrasi
        'category',
        'time',
        'title',
        'description',
        'speaker',
        'position',
        // 'image' dihapus karena sudah di-drop di migrasi
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Relasi dengan booths
     */
    public function booths()
    {
        return $this->hasMany(Booth::class);
    }

    /**
     * Relasi dengan bookings melalui booths
     */
    public function bookings()
    {
        return $this->hasManyThrough(Booking::class, Booth::class);
    }

    /**
     * Accessor untuk mendapatkan nama event - hanya main_title
     */
    public function getEventNameAttribute()
    {
        return $this->main_title ?? 'Event #' . $this->id;
    }

    /**
     * Accessor untuk mendapatkan lokasi lengkap
     */
    public function getFullLocationAttribute()
    {
        return $this->location ?? 'Lokasi belum ditentukan';
    }

    /**
     * Scope untuk event yang memiliki booking
     */
    public function scopeWithBookings($query)
    {
        return $query->whereHas('booths.bookings');
    }

    /**
     * Scope untuk filter berdasarkan kategori
     */
    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope untuk event yang sedang berlangsung
     */
    public function scopeActive($query)
    {
        return $query->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    /**
     * Scope untuk event yang akan datang
     */
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
    }
}