<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Denah extends Model
{
    use HasFactory;

    protected $fillable = [
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
    ];

    // Get section color based on section type
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

    // Scope for available booths
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // Scope for booked booths
    public function scopeBooked($query)
    {
        return $query->where('status', 'booked');
    }
}