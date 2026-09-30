<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categori extends Model
{
    // Nama tabel
    protected $table = 'categoris';

    // Field yang bisa diisi secara massal
    protected $fillable = [
        'name',
        'price',
        'subtitle',
        'facilities',
        'event_id', // Tambahkan event_id ke fillable
    ];

    // Relasi dengan Event (jika ada model Event)
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Optional: accessor untuk mengubah string facilities menjadi array
    public function getFacilitiesArrayAttribute()
    {
        return array_map('trim', explode(',', $this->facilities));
    }

    // Optional: mutator untuk memformat harga
    public function getPriceFormattedAttribute()
    {
        // Jika price berupa angka, format dengan number_format
        if (is_numeric($this->price)) {
            return 'Rp ' . number_format($this->price, 0, ',', '.');
        }
        return $this->price;
    }
}