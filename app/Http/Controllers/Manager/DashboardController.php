<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Blog;
use App\Models\Contact;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Get manager dashboard statistics
        $stats = [
            'total_users' => User::count(),
            'total_events' => Event::count(),
            'total_tickets' => Ticket::count(),
            'total_blogs' => Blog::count(),
            'total_messages' => Contact::count(),
            'pending_requests' => Event::where('status', 'pending')->count(),
            'total_revenue' => Ticket::where('payment_status', 'paid')->sum('total_amount'),
            'recent_users' => User::where('created_at', '>=', now()->subDays(7))->count(),
        ];

        // Recent activities (limited view for manager)
        $recent_tickets = Ticket::with(['user', 'event'])
            ->latest()
            ->take(5)
            ->get();

        $recent_requests = Event::with('creator')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        $recent_messages = Contact::latest()
            ->take(5)
            ->get();

        return view('manager.dashboard', compact('stats', 'recent_tickets', 'recent_requests', 'recent_messages'));
    }
}
