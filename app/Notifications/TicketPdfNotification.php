<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Ticket;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketPdfNotification extends Notification
{
    use Queueable;

    public $ticket;

    /**
     * Create a new notification instance.
     */
    public function __construct(Ticket $ticket)
    {
        $this->ticket = $ticket;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Generate PDF for email attachment
        $pdf = Pdf::loadView('tickets.pdf', ['ticket' => $this->ticket]);
        $pdfContent = $pdf->output();
        
        // Create filename
        $filename = 'ticket-' . $this->ticket->ticket_code . '.pdf';
        
        $mailMessage = (new MailMessage)
            ->subject('🎫 Your Event Ticket - ' . $this->ticket->event->title)
            ->greeting('Hello ' . $this->ticket->user->name . '!')
            ->line('Thank you for your booking! Your event ticket is ready.')
            ->line('**Event Details:**')
            ->line('🎪 **Event:** ' . $this->ticket->event->title)
            ->line('📅 **Date:** ' . ($this->ticket->event->starts_at ? $this->ticket->event->starts_at->format('l, F j, Y g:i A') : 'TBA'))
            ->line('📍 **Venue:** ' . ($this->ticket->event->venue ?? $this->ticket->event->location ?? 'TBA'))
            ->line('🎫 **Ticket Code:** ' . $this->ticket->ticket_code)
            ->line('🔢 **Quantity:** ' . $this->ticket->quantity . ' ' . ($this->ticket->quantity > 1 ? 'tickets' : 'ticket'))
            ->line('💰 **Total Amount:** ৳' . number_format($this->ticket->total_amount, 2))
            ->line('💳 **Payment Status:** ' . ucfirst($this->ticket->payment_status));
            
        if ($this->ticket->payment_status === 'paid') {
            $mailMessage->line('✅ Your payment has been confirmed and your ticket is valid for entry.')
                ->action('View Ticket Online', route('tickets.show', $this->ticket));
        } else {
            $mailMessage->line('⏳ Your ticket is generated but payment verification is pending.')
                ->line('You will receive another confirmation email once your payment is verified.')
                ->action('Complete Payment', route('tickets.show', $this->ticket));
        }
            
        $mailMessage->line('📱 **Important:** Please bring this ticket (printed or on your mobile device) and a valid ID to the event.')
            ->line('Your PDF ticket is attached to this email for your convenience.')
            ->line('If you have any questions, please don\'t hesitate to contact us.')
            ->line('See you at the event! 🎉')
            ->salutation('Regards,  
EventEase Team')
            ->attachData($pdfContent, $filename, [
                'mime' => 'application/pdf',
            ]);

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'ticket_id' => $this->ticket->id,
            'ticket_code' => $this->ticket->ticket_code,
            'event_title' => $this->ticket->event->title,
            'payment_status' => $this->ticket->payment_status,
            'sent_at' => now(),
        ];
    }
}
