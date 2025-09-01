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
        
        // Prepare event details for the email
        $eventDetails = '
            <div style="margin: 10px 0;">
                <strong>Event:</strong> ' . $this->ticket->event->title . '
            </div>
            <div style="margin: 10px 0;">
                <strong>📅 Date:</strong> ' . ($this->ticket->event->starts_at ? $this->ticket->event->starts_at->format('l, F j, Y g:i A') : 'TBA') . '
            </div>
            <div style="margin: 10px 0;">
                <strong>📍 Venue:</strong> ' . ($this->ticket->event->venue ?? $this->ticket->event->location ?? 'TBA') . '
            </div>
            <div style="margin: 10px 0;">
                <strong>🎫 Ticket Code:</strong> ' . $this->ticket->ticket_code . '
            </div>
            <div style="margin: 10px 0;">
                <strong>🔢 Quantity:</strong> ' . $this->ticket->quantity . ' ' . ($this->ticket->quantity > 1 ? 'tickets' : 'ticket') . '
            </div>
            <div style="margin: 10px 0;">
                <strong>💰 Total Amount:</strong> ৳' . number_format($this->ticket->total_amount, 2) . '
            </div>
            <div style="margin: 10px 0;">
                <strong>💳 Payment Status:</strong> ' . ucfirst($this->ticket->payment_status) . '
            </div>
        ';
        
        $contentLines = [];
        $actionUrl = route('tickets.show', $this->ticket);
        $actionText = 'View Ticket Online';
        
        if ($this->ticket->payment_status === 'paid') {
            $contentLines[] = '✅ Your payment has been confirmed and your ticket is valid for entry.';
        } else {
            $contentLines[] = '⏳ Your ticket is generated but payment verification is pending.';
            $contentLines[] = 'You will receive another confirmation email once your payment is verified.';
            $actionText = 'Complete Payment';
        }
        
        $importantNote = 'Please bring this ticket (printed or on your mobile device) and a valid ID to the event.<br><br>Your PDF ticket is attached to this email for your convenience.';
        
        $mailMessage = (new MailMessage)
            ->subject('🎫 Your Event Ticket - ' . $this->ticket->event->title)
            ->view('emails.ticket-notification', [
                'subject' => '🎫 Your Event Ticket - ' . $this->ticket->event->title,
                'headerTitle' => 'Your Event Ticket',
                'greeting' => 'Hello ' . $this->ticket->user->name . '!',
                'introLine' => 'Thank you for your booking! Your event ticket is ready.',
                'eventDetails' => $eventDetails,
                'contentLines' => $contentLines,
                'actionUrl' => $actionUrl,
                'actionText' => $actionText,
                'importantNote' => $importantNote,
                'closingLine' => 'If you have any questions, please don\'t hesitate to contact us.<br><br>See you at the event! 🎉',
            ])
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
