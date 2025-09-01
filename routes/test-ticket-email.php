<?php

use Illuminate\Support\Facades\Route;
use App\Models\Ticket;
use App\Notifications\TicketPdfNotification;

Route::get('/test-ticket-email/{ticket}', function (Ticket $ticket) {
    try {
        $ticket->user->notify(new TicketPdfNotification($ticket));
        
        return response()->json([
            'success' => true,
            'message' => 'Email sent successfully to ' . $ticket->user->email,
            'ticket' => [
                'id' => $ticket->id,
                'code' => $ticket->ticket_code,
                'user' => $ticket->user->name,
                'event' => $ticket->event->title,
                'status' => $ticket->payment_status
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Failed to send email: ' . $e->getMessage(),
            'error' => $e->getTraceAsString()
        ], 500);
    }
})->name('test.ticket.email');
