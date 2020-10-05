<?php

namespace App\Notifications;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as NotificationsResetPassword;

class ResetPassword extends NotificationsResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = url(config('app.client_url') . '/reset-password/' . $this->token) . '?email=' . urlencode($notifiable->email);


        return (new MailMessage)
            ->subject(Lang::get('Reset Password Notification'))
            ->greeting('Hello ' . Str::ucfirst($notifiable->name) . '!')
            ->line(Lang::get('You are receiving this email because we received a password reset request for your account.'))
            ->action(Lang::get('Reset Password'), $url)
            ->line(Lang::get('This password reset link will expire in :count minutes.', ['count' => config('auth.passwords.' . config('auth.defaults.passwords') . '.expire')]))
            ->line(Lang::get('If you did not request a password reset, no further action is required.'));
    }
}
