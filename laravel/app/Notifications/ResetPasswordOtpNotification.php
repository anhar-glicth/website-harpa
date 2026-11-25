<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ResetPasswordOtpNotification extends Notification
{
    use Queueable;

    public function __construct(public string $otp)
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $appName = config('app.name', 'Harapan Palestina');

        return (new MailMessage)
            ->subject('Kode OTP Reset Kata Sandi - ' . $appName)
            ->greeting('Halo, ' . ($notifiable->name ?: 'Sahabat') . '!')
            ->line('Kami menerima permintaan untuk mereset kata sandi akun Anda di ' . $appName . '.')
            ->line('Berikut adalah 6 digit kode OTP verifikasi Anda:')
            ->line(new HtmlString(
                '<div style="text-align: center; margin: 24px 0;">' .
                '<span style="display: inline-block; font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace; font-size: 32px; font-weight: 800; letter-spacing: 8px; color: #063f5c; background-color: #edf5f9; padding: 14px 28px; border-radius: 12px; border: 2px dashed #063f5c;">' .
                $this->otp .
                '</span>' .
                '</div>'
            ))
            ->line('Kode OTP ini hanya berlaku selama 15 menit.')
            ->line('Demi keamanan akun Anda, jangan berikan kode OTP ini kepada siapa pun.')
            ->line('Jika Anda tidak merasa meminta reset kata sandi, abaikan email ini dan akun Anda tetap aman.')
            ->salutation("Salam hangat,  \nTim " . $appName);
    }
}
