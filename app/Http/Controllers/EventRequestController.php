<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventRequestController extends Controller
{
    public function create()
    {
        return view('events.request');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'location'         => 'nullable|string|max:255',
            'venue'            => 'nullable|string|max:255',
            'starts_at'        => 'required|date|after:now',
            'ends_at'          => 'nullable|date|after_or_equal:starts_at',
            'capacity'         => 'nullable|integer|min:0',
            'price'            => 'nullable|numeric|min:0',
            'event_type'       => 'required|in:free,paid',
            'event_status'     => 'required|in:available,limited_sell,sold_out',
            'purchase_option'  => 'required|in:both,pay_now,pay_later',
            'banner'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'featured_on_home' => 'nullable|boolean',
            'visible_on_site'  => 'nullable|boolean',
        ]);

        // Ensure folder exists
        $uploadDir = public_path('uploads/events');
        if (!is_dir($uploadDir)) {
            @mkdir($uploadDir, 0775, true);
        }

        // Handle banner upload
        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $ext = $request->file('banner')->extension();
            $file = 'ev_' . time() . '_' . mt_rand(1000, 9999) . '.' . $ext;
            $request->file('banner')->move($uploadDir, $file);

            // Store RELATIVE path (no leading slash)
            $bannerPath = 'uploads/events/' . $file;
        }

        // Prepare event data for creation
        $eventData = [
            'title'            => $data['title'],
            'description'      => $data['description'],
            'location'         => $data['location'],
            'venue'            => $data['venue'],
            'starts_at'        => $data['starts_at'],
            'ends_at'          => $data['ends_at'],
            'capacity'         => $data['capacity'],
            'price'            => $data['price'] ?? 0,
            'event_type'       => $data['event_type'],
            'event_status'     => $data['event_status'],
            'purchase_option'  => $data['purchase_option'],
            'banner'           => $bannerPath,
            'banner_path'      => $bannerPath, // Keep both for compatibility
            'featured_on_home' => $request->boolean('featured_on_home', false),
            'visible_on_site'  => $request->boolean('visible_on_site', true),
            'allow_pay_later'  => $data['purchase_option'] === 'pay_later' || $data['purchase_option'] === 'both',
            'created_by'       => Auth::id(),
            'status'           => 'pending',
        ];

        Event::create($eventData);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Event request submitted successfully! Our team will review it within 24-48 hours and notify you via email.');
    }
}
