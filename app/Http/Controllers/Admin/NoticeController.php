<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notice;
use App\Models\NoticeSettings;
use Illuminate\Http\Request;

class NoticeController extends Controller
{
    public function index()
    {
        $notices = Notice::orderBy('priority', 'desc')->orderBy('created_at', 'desc')->get();
        $settings = NoticeSettings::getSettings();
        
        return view('admin.notices.index', compact('notices', 'settings'));
    }

    public function create()
    {
        return view('admin.notices.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        Notice::create([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->boolean('is_active'),
            'priority' => $request->priority ?? 0,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'type' => 'marquee',
            'is_marquee' => true,
        ]);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice created successfully!');
    }

    public function show(Notice $notice)
    {
        return view('admin.notices.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        return view('admin.notices.edit', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'is_active' => 'boolean',
            'priority' => 'integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $notice->update([
            'title' => $request->title,
            'content' => $request->content,
            'is_active' => $request->boolean('is_active'),
            'priority' => $request->priority ?? $notice->priority,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice updated successfully!');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        
        return redirect()->route('admin.notices.index')
            ->with('success', 'Notice deleted successfully!');
    }

    // Toggle notice bar visibility
    public function toggleSettings(Request $request)
    {
        $settings = NoticeSettings::getSettings();
        $settings->update([
            'is_enabled' => $request->boolean('is_enabled'),
            'scroll_speed' => $request->scroll_speed ?? 'normal',
            'background_color' => $request->background_color ?? '#1e3a8a',
            'text_color' => $request->text_color ?? '#ffffff',
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_enabled' => $settings->is_enabled,
                'message' => 'Notice bar settings updated successfully!'
            ]);
        }

        return redirect()->back()
            ->with('success', 'Notice bar settings updated successfully!');
    }
}
