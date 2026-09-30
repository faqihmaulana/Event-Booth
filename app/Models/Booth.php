<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booth extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',       
        'booth_id',
        'booth_name',
        'section',
        'price',
        'position_x',
        'position_y',
        'width',
        'height',
        'status',
        'color'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'position_x' => 'integer',
        'position_y' => 'integer',
        'width' => 'integer',
        'height' => 'integer',
        'event_id' => 'integer',  // Tambahkan cast ini
    ];

    // Mendapatkan warna section berdasarkan tipe section
    public function getSectionColorAttribute()
    {
        $colors = [
            'A' => '#FF69B4', // Pink
            'B' => '#4CAF50', // Green
            'C' => '#FF9800', // Orange
            'D' => '#8D6E63', // Brown
            'E' => '#2196F3', // Blue
            'F' => '#F44336', // Red
            'T' => '#FFF176', // Yellow
        ];

        return $colors[$this->section] ?? '#6c757d';
    }

    // Scope untuk booth yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Scope untuk booth yang sudah dipesan
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }

    // Relasi dengan Event (jika Anda punya model Event)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}