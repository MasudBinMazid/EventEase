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

        $contentLines = [
            'Thank you for creating an account with EventEase, your trusted partner for event discovery and ticket booking.',
            'To get started and access all our amazing features, please verify your email address by clicking the button below.',
            'This verification link will expire in ' . config('auth.verification.expire', 60) . ' minutes.',
            'If you did not create an account with EventEase, no further action is required.'
        ];

        return (new MailMessage)
            ->subject('Welcome to EventEase - Verify Your Email Address')
            ->view('emails.auth-notification', [
                'subject' => 'Welcome to EventEase - Verify Your Email Address',
                'headerTitle' => 'Email Verification',
                'greeting' => 'Welcome to EventEase, ' . $notifiable->name . '!',
                'introLine' => '🎉 Welcome to EventEase! We\'re excited to have you join our community.',
                'contentLines' => $contentLines,
                'actionUrl' => $verificationUrl,
                'actionText' => '✅ Verify Email Address',
                'importantNote' => '<strong>🔒 Security Note:</strong> This is an automated email. If you didn\'t create an EventEase account, you can safely ignore this message.',
                'closingLines' => [
                    'Welcome to the EventEase community! 🎉',
                    'Get ready to discover amazing events and book tickets with ease.'
                ]
            ]);
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