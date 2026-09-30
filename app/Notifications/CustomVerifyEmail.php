<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends BaseVerifyEmail
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('🎉 Selamat Datang! Verifikasi Email Anda')
            ->greeting('Halo ' . $notifiable->name . '! 👋')
            ->line('Terima kasih telah bergabung dengan **' . config('app.name') . '**!')
            ->line('Kami sangat senang Anda menjadi bagian dari komunitas kami.')
            ->line('Untuk melengkapi pendaftaran dan mengakses semua fitur, silakan verifikasi alamat email Anda dengan mengklik tombol di bawah ini:')
            ->action('✅ Verifikasi Email Saya', $verificationUrl)
            ->line('**Mengapa perlu verifikasi?**')
            ->line('• Melindungi akun Anda dari penyalahgunaan')
            ->line('• Memastikan Anda mendapat notifikasi penting')
            ->line('• Mengakses semua fitur aplikasi')
            ->line('---')
            ->line('Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser Anda:')
            ->line($verificationUrl)
            ->line('---')
            ->line('**Catatan Penting:**')
            ->line('• Tautan ini akan kedaluwarsa dalam 60 menit')
            ->line('• Jika Anda tidak mendaftar akun ini, abaikan email ini')
            ->line('• Jangan bagikan tautan ini kepada orang lain')
            ->salutation('Salam hangat,<br>**Tim ' . config('app.name') . '** 🚀')
            ->with([
                'user' => $notifiable,
                'url' => $verificationUrl
            ]);
    }
}