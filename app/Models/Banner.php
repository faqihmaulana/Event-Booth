<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'subtitle',
        'event_date',
        'event_location',
        'image_path',
        'countdown_enabled',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'event_date' => 'date',
        'countdown_enabled' => 'boolean',
    ];

    /**
     * Get the galleries for the banner.
     */
    public function galleries()
    {
        return $this->hasMany(Gallery::class);
    }

    /**
     * Get active galleries for the banner.
     */
    public function activeGalleries()
    {
        return $this->hasMany(Gallery::class)->active()->ordered();
    }
}