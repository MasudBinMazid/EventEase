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
        $request->validate([
            'title'            => 'required|string|max:255',
            'description'      => 'nullable|string',
            'banner'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            'location'         => 'nullable|string|max:255',
            'starts_at'        => 'required|date|after:now',
            'ends_at'          => 'nullable|date|after_or_equal:starts_at',
            'capacity'         => 'nullable|integer|min:1|max:10000',
            'is_paid'          => 'nullable|boolean',
            'price'            => 'nullable|numeric|min:0',
            'currency'         => 'nullable|string|in:BDT,USD,EUR',
            'allow_pay_later'  => 'nullable|boolean',
        ]);

        // Handle banner upload
        $bannerPath = null;
        if ($request->hasFile('banner')) {
            $banner = $request->file('banner');
            $filename = time() . '_' . $banner->getClientOriginalName();
            $bannerPath = $banner->move(public_path('uploads/events'), $filename);
            $bannerPath = 'uploads/events/' . $filename;
        }

        // Determine pricing
        $isPaid = $request->boolean('is_paid');
        $price = $isPaid && $request->filled('price') ? $request->price : 0;

        Event::create([
            'title'            => $request->title,
            'description'      => $request->description,
            'banner_path'      => $bannerPath,
            'location'         => $request->location,
            'starts_at'        => $request->starts_at,
            'ends_at'          => $request->ends_at,
            'capacity'         => $request->capacity,
            'price'            => $price,
            'allow_pay_later'  => $request->boolean('allow_pay_later', true),
            'created_by'       => Auth::id(),
            'status'           => 'pending',
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Event request submitted successfully! Our team will review it within 24-48 hours and notify you via email.');
    }
}
