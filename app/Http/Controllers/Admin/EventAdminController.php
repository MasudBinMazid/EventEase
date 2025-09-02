<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketType;
use Illuminate\Http\Request;

class EventAdminController extends Controller
{
    public function index()
    {
        $events = Event::select('id','title','location','starts_at','ends_at','capacity','featured_on_home','visible_on_site')
                       ->orderByDesc('starts_at')
                       ->get();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'location'         => 'nullable|string|max:255',
            'venue'            => 'nullable|string|max:255',
            'starts_at'        => 'required|date',
            'ends_at'          => 'nullable|date|after_or_equal:starts_at',
            'capacity'         => 'nullable|integer|min:0',
            'price'            => 'nullable|numeric|min:0',
            'event_type'       => 'required|in:free,paid',
            'event_status'     => 'required|in:available,limited_sell,sold_out',
            'purchase_option'  => 'required|in:both,pay_now,pay_later',
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_on_home' => 'nullable|boolean',
            'visible_on_site'  => 'nullable|boolean',
            'ticket_types'     => 'nullable|array',
            'ticket_types.*.name' => 'nullable|string|max:255',
            'ticket_types.*.price' => 'nullable|numeric|min:0',
            'ticket_types.*.description' => 'nullable|string',
            'ticket_types.*.quantity_available' => 'nullable|integer|min:1',
            'ticket_types.*.status' => 'nullable|in:available,sold_out',
            'ticket_types.*.sort_order' => 'nullable|integer',
        ]);

        // Ensure folder exists
        $uploadDir = public_path('uploads/events');
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        $bannerPath = null;
        if ($r->hasFile('banner')) {
            $ext  = $r->file('banner')->extension();
            $file = 'ev_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            $r->file('banner')->move($uploadDir, $file);

            // Store RELATIVE path (no leading slash)
            $bannerPath = 'uploads/events/' . $file;
        }

        // Prepare event data
        $eventData = array_merge($data, [
            'banner'           => $bannerPath,
            'created_by'       => auth()->id(),
            'status'           => 'approved',
            'featured_on_home' => $r->boolean('featured_on_home'),
            'visible_on_site'  => $r->boolean('visible_on_site', true), // Default to true
        ]);

        // Remove ticket_types from event data as it will be handled separately
        unset($eventData['ticket_types']);

        // Create the event
        $event = Event::create($eventData);

        // Handle ticket types for paid events
        if ($data['event_type'] === 'paid' && !empty($data['ticket_types'])) {
            foreach ($data['ticket_types'] as $ticketTypeData) {
                $event->ticketTypes()->create([
                    'name' => $ticketTypeData['name'],
                    'price' => $ticketTypeData['price'],
                    'description' => $ticketTypeData['description'] ?? null,
                    'quantity_available' => $ticketTypeData['quantity_available'] ?: null,
                    'status' => $ticketTypeData['status'],
                    'sort_order' => $ticketTypeData['sort_order'] ?? 0,
                    'quantity_sold' => 0,
                ]);
            }
        }

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
        $event->load('ticketTypes');
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $r, Event $event)
    {
        $data = $r->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'location'         => 'nullable|string|max:255',
            'venue'            => 'nullable|string|max:255',
            'starts_at'        => 'nullable|date',
            'ends_at'          => 'nullable|date|after_or_equal:starts_at',
            'capacity'         => 'nullable|integer|min:0',
            'price'            => 'nullable|numeric|min:0',
            'event_type'       => 'required|in:free,paid',
            'event_status'     => 'nullable|in:available,limited_sell,sold_out',
            'purchase_option'  => 'required|in:both,pay_now,pay_later',
            'remove_banner'    => 'nullable|boolean',
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_on_home' => 'nullable|boolean',
            'visible_on_site'  => 'nullable|boolean',
            'ticket_types'     => 'nullable|array',
            'ticket_types.*.id' => 'nullable|integer|exists:ticket_types,id',
            'ticket_types.*.name' => 'required_with:ticket_types|string|max:255',
            'ticket_types.*.price' => 'required_with:ticket_types|numeric|min:0',
            'ticket_types.*.description' => 'nullable|string',
            'ticket_types.*.quantity_available' => 'nullable|integer|min:1',
            'ticket_types.*.status' => 'required_with:ticket_types|in:available,sold_out',
            'ticket_types.*.sort_order' => 'nullable|integer',
        ]);

        $uploadDir = public_path('uploads/events');
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        $bannerPath = $event->banner; // keep current path by default

        if ($r->hasFile('banner')) {
            // Delete old file (supports legacy leading slash)
            if ($event->banner) {
                $old = public_path(ltrim($event->banner, '/'));
                if (is_file($old)) @unlink($old);
            }

            $ext  = $r->file('banner')->extension();
            $file = 'ev_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            $r->file('banner')->move($uploadDir, $file);

            $bannerPath = 'uploads/events/' . $file;

        } elseif ($r->boolean('remove_banner')) {
            if ($event->banner) {
                $old = public_path(ltrim($event->banner, '/'));
                if (is_file($old)) @unlink($old);
            }
            $bannerPath = null;
        }

        // Prepare event data (remove ticket_types as it will be handled separately)
        $eventData = $data;
        unset($eventData['ticket_types']);
        unset($eventData['remove_banner']);
        unset($eventData['banner']);

        // Overwrite the file field with the final path
        $payload = array_merge($eventData, [
            'banner'           => $bannerPath,
            'featured_on_home' => $r->boolean('featured_on_home'),
            'visible_on_site'  => $r->boolean('visible_on_site'),
        ]);

        // Update the event
        $event->update($payload);

        // Handle ticket types for paid events
        if ($data['event_type'] === 'paid' && !empty($data['ticket_types'])) {
            // Get existing ticket type IDs
            $existingIds = collect($data['ticket_types'])
                ->pluck('id')
                ->filter()
                ->toArray();
            
            // Delete ticket types that are no longer in the form
            $event->ticketTypes()
                ->whereNotIn('id', $existingIds)
                ->delete();
            
            // Update or create ticket types
            foreach ($data['ticket_types'] as $ticketTypeData) {
                if (!empty($ticketTypeData['id'])) {
                    // Update existing ticket type
                    $ticketType = $event->ticketTypes()->find($ticketTypeData['id']);
                    if ($ticketType) {
                        $ticketType->update([
                            'name' => $ticketTypeData['name'],
                            'price' => $ticketTypeData['price'],
                            'description' => $ticketTypeData['description'] ?? null,
                            'quantity_available' => $ticketTypeData['quantity_available'] ?? null,
                            'status' => $ticketTypeData['status'],
                            'sort_order' => $ticketTypeData['sort_order'] ?? 1,
                        ]);
                    }
                } else {
                    // Create new ticket type
                    $event->ticketTypes()->create([
                        'name' => $ticketTypeData['name'],
                        'price' => $ticketTypeData['price'],
                        'description' => $ticketTypeData['description'] ?? null,
                        'quantity_available' => $ticketTypeData['quantity_available'] ?? null,
                        'quantity_sold' => 0,
                        'status' => $ticketTypeData['status'],
                        'sort_order' => $ticketTypeData['sort_order'] ?? 1,
                    ]);
                }
            }
        } else {
            // If event is now free, delete all ticket types
            $event->ticketTypes()->delete();
        }

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        if ($event->banner) {
            $old = public_path(ltrim($event->banner, '/'));
            if (is_file($old)) @unlink($old);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }
}
