<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventAdminController extends Controller
{
    public function index()
    {
        $events = Event::select('id','title','location','starts_at','ends_at','capacity','featured_on_home')
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
            'purchase_option'  => 'required|in:both,pay_now,pay_later',
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_on_home' => 'nullable|boolean',
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

        // Overwrite the file field with the final path
        $payload = array_merge($data, [
            'banner'           => $bannerPath,
            'created_by'       => auth()->id(),
            'status'           => 'approved',
            'featured_on_home' => $r->boolean('featured_on_home'),
        ]);

        Event::create($payload);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function edit(Event $event)
    {
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
            'purchase_option'  => 'required|in:both,pay_now,pay_later',
            'remove_banner'    => 'nullable|boolean',
            'banner'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'featured_on_home' => 'nullable|boolean',
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

        // Overwrite the file field with the final path
        $payload = array_merge($data, [
            'banner'           => $bannerPath,
            'featured_on_home' => $r->boolean('featured_on_home'),
        ]);

        $event->update($payload);

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
