<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $this->token);
        }

        $url = $this->resetUrl($notifiable);
        $expireMinutes = config('auth.passwords.' . config('auth.defaults.passwords') . '.expire', 60);
        $appName = config('app.name', 'Harapan Palestina');

        return (new MailMessage)
            ->subject('Reset Kata Sandi - ' . $appName)
            ->greeting('Halo, ' . ($notifiable->name ?: 'Sahabat') . '!')
            ->line('Anda menerima email ini karena kami menerima permintaan reset kata sandi untuk akun Anda di ' . $appName . '.')
            ->action('Reset Kata Sandi', $url)
            ->line('Tautan reset kata sandi ini hanya berlaku selama ' . $expireMinutes . ' menit.')
            ->line('Jika Anda tidak merasa meminta reset kata sandi, abaikan email ini dan akun Anda tetap aman.')
            ->salutation("Salam hangat,  \nTim " . $appName);
    }
}
