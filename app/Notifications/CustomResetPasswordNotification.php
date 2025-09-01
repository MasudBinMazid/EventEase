<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    protected $token;

    /**
     * Create a new notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $resetUrl = url(config('app.url') . route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $contentLines = [
            'You are receiving this email because we received a password reset request for your EventEase account.',
            'This password reset link will expire in ' . config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') . ' minutes.',
            'If you did not request a password reset, no further action is required. Your account remains secure.'
        ];

        return (new MailMessage)
            ->subject('🔐 Reset Your EventEase Password')
            ->view('emails.auth-notification', [
                'subject' => '🔐 Reset Your EventEase Password',
                'headerTitle' => 'Password Reset',
                'greeting' => 'Hello ' . $notifiable->name . '!',
                'introLine' => '🔑 We received a request to reset your EventEase account password.',
                'contentLines' => $contentLines,
                'actionUrl' => $resetUrl,
                'actionText' => '🔐 Reset Password',
                'importantNote' => '<strong>🛡️ Security Reminder:</strong> For security reasons, please do not share this link with anyone. If you didn\'t request this reset, your account is still secure.',
                'closingLines' => [
                    'If you have any questions or need help, feel free to contact our support team.',
                    'Stay secure! 🛡️'
                ]
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
