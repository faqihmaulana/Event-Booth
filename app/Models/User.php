<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\CustomVerifyEmail;
use App\Notifications\CustomResetPasswordNotification;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'phone',
        'address',
        'company',
        'position',
        'website',
        'bio',
        'profile_photo',
        'email_verified_at',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting tipe data.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Send the email verification notification.
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }

    /**
     * Relasi ke tabel roles.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Mengecek apakah user memiliki role tertentu.
     */
    public function hasRole($roleName)
    {
        return $this->role->name === $roleName;
    }

    /**
     * Mengecek apakah user adalah admin.
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Mengecek apakah user adalah tenant.
     */
    public function isTenant()
    {
        return $this->hasRole('tenant');
    }
}