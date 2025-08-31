<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator')
            ->visibleOnSite()
            ->orderBy('starts_at', 'asc')
            ->paginate(9);

        return view('events.index', compact('events'));
    }

    public function show(Event $event)
    {
        // Allow viewing if event is visible on site and approved
        // OR if user is admin or creator
        if ($event->status !== 'approved' || !$event->visible_on_site) {
            $user = Auth::user();
            if (!$user || (!$user->isAdmin() && $user->id !== $event->created_by)) {
                abort(404);
            }
        }
        
        // Load the creator relationship
        $event->load('creator');
        
        return view('events.show', compact('event'));
    }
}
