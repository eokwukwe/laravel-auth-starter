<?php

namespace App\Notifications;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\VerifyEmail as NotificationsVerifyEmail;

class VerifyEmail extends NotificationsVerifyEmail
{
    /**
    * Get the verification URL for the given notifiable.
    * The URL will be for the frontend.
    *
    * This overwrites the default verificationUrl method in the core verifyEmail class
    *
    * @param  mixed  $notifiable
    * @return string
    */
    protected function verificationUrl($notifiable)
    {
        $appUrl = config('app.client_url', config('app.url'));

        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['user' => $notifiable->id]
        );

        // Return the URL that can be used in the front end
        return str_replace(url('/api/v1'), $appUrl, $url);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->greeting('Hello ' . Str::ucfirst($notifiable->name) . '!')
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email Address'), $verificationUrl)
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }
}
