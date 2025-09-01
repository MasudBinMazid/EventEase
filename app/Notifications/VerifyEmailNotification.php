<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailNotification extends VerifyEmail
{
    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Welcome to EventEase - Verify Your Email Address')
            ->greeting('Welcome to EventEase!')
            ->line('Thank you for creating an account with EventEase, your trusted partner for event discovery and ticket booking.')
            ->line('To get started and access all our amazing features, please verify your email address by clicking the button below.')
            ->action('Verify Email Address', $verificationUrl)
            ->line('This verification link will expire in ' . config('auth.verification.expire', 60) . ' minutes.')
            ->line('If you did not create an account with EventEase, no further action is required.')
            ->line('Welcome to the EventEase community! 🎉')
            ->salutation('Best regards,<br>The EventEase Team');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}