<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Notifications\TicketPdfNotification;
use Illuminate\Http\Request;

class PaymentReceivedController extends Controller
{
    public function index(Request $r)
    {
        $q       = trim((string) $r->input('q', ''));
        $eventId = (int) $r->input('event_id', 0);

        $rows = Ticket::query()
            ->join('users',  'users.id',  '=', 'tickets.user_id')
            ->join('events', 'events.id', '=', 'tickets.event_id')
            ->where('tickets.payment_option', 'pay_now')
            ->where('tickets.payment_status', 'unpaid')
            ->when($eventId > 0, fn($qq) => $qq->where('tickets.event_id', $eventId))
            ->when($q !== '', function ($qq) use ($q) {
                $qq->where(function ($w) use ($q) {
                    $w->where('tickets.ticket_code', 'like', "%$q%")
                      ->orWhere('users.name', 'like', "%$q%")
                      ->orWhere('users.email', 'like', "%$q%")
                      ->orWhere('tickets.payment_txn_id', 'like', "%$q%")
                      ->orWhere('tickets.payer_number', 'like', "%$q%");
                });
            })
            ->select([
                'tickets.id','tickets.ticket_code','tickets.quantity','tickets.total_amount',
                'tickets.payment_txn_id','tickets.payer_number','tickets.payment_proof_path',
                'tickets.created_at',
                'events.id as event_id','events.title as event_title',
                'users.id as user_id','users.name as buyer_name','users.email as buyer_email','users.phone as buyer_phone',
            ])
            ->orderByDesc('tickets.created_at')
            ->paginate(25)
            ->withQueryString();

        $events = \App\Models\Event::select('id','title')->orderByDesc('starts_at')->get();

        return view('admin.payments_received.index', compact('rows','events','q','eventId'));
    }

    public function verify(\App\Models\Ticket $ticket)
    {
        if ($ticket->payment_status !== 'unpaid' || $ticket->payment_option !== 'pay_now') {
            return back()->with('error', 'Ticket is not eligible for verification.');
        }

        $ticket->update([
            'payment_status'      => 'paid',
            'payment_verified_at' => now(),
            'payment_verified_by' => auth()->id(),
        ]);

        // Send updated ticket email with payment confirmed status
        try {
            $ticket->user->notify(new TicketPdfNotification($ticket));
        } catch (\Exception $e) {
            // Log the error but don't fail the verification process
            \Log::error('Failed to send payment verification email notification', [
                'ticket_id' => $ticket->id,
                'user_id' => $ticket->user_id,
                'error' => $e->getMessage()
            ]);
        }

        return back()->with('success', "Ticket #{$ticket->id} marked as paid and confirmation email sent.");
    }
}
