<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Ticket;
use Illuminate\Http\Request;

class OrganizerController extends Controller
{
    public function index()
    {
        $organizer = auth()->user();
        
        // Get events created by this organizer only
        $events = Event::where('created_by', $organizer->id)
                      ->with(['creator', 'tickets'])
                      ->orderByDesc('created_at')
                      ->get();
                      
        // Calculate statistics
        $totalEvents = $events->count();
        $approvedEvents = $events->where('status', 'approved')->count();
        $pendingEvents = $events->where('status', 'pending')->count();
        $rejectedEvents = $events->where('status', 'rejected')->count();
        
        // Calculate ticket sales for organizer's events
        $eventIds = $events->pluck('id');
        $totalTickets = Ticket::whereIn('event_id', $eventIds)
                             ->where('payment_status', 'paid')
                             ->sum('quantity');
                             
        $totalRevenue = Ticket::whereIn('event_id', $eventIds)
                             ->where('payment_status', 'paid')
                             ->sum('total_amount');

        return view('organizer.dashboard', compact(
            'events',
            'totalEvents',
            'approvedEvents',
            'pendingEvents',
            'rejectedEvents',
            'totalTickets',
            'totalRevenue'
        ));
    }

    public function show(Event $event)
    {
        // Ensure organizer can only view their own events
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Access denied. You can only view events you created.');
        }

        // Load relationships
        $event->load(['creator', 'tickets.user']);
        
        // Calculate event statistics
        $ticketStats = [
            'total_sold' => $event->tickets()->where('payment_status', 'paid')->sum('quantity'),
            'total_revenue' => $event->tickets()->where('payment_status', 'paid')->sum('total_amount'),
            'pending_payments' => $event->tickets()->where('payment_status', 'unpaid')->count(),
            'unique_buyers' => $event->tickets()->where('payment_status', 'paid')->distinct('user_id')->count(),
        ];

        return view('organizer.events.show', compact('event', 'ticketStats'));
    }

    public function tickets(Event $event)
    {
        // Ensure organizer can only view their own event tickets
        if ($event->created_by !== auth()->id()) {
            abort(403, 'Access denied. You can only view tickets for events you created.');
        }

        $tickets = Ticket::where('event_id', $event->id)
                         ->with(['user', 'event'])
                         ->orderByDesc('created_at')
                         ->paginate(20);

        return view('organizer.tickets.index', compact('event', 'tickets'));
    }
}
