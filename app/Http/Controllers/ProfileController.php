<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\Ticket;
use App\Models\UserNotification;

class ProfileController extends Controller
{
  
   

    public function dashboard()
    {
        $user = auth()->user();
        
        // Redirect based on user role
        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        }
        
        if ($user->isManager()) {
            return redirect()->route('admin.index');
        }
        
        if ($user->isOrganizer()) {
            return redirect()->route('organizer.dashboard');
        }
        
        // Regular user dashboard - show only valid tickets (not entered)
        $tickets = Ticket::with('event')
            ->where('user_id', $user->id)
            ->where(function($query) {
                $query->where('entry_status', 'not_entered')
                      ->orWhereNull('entry_status');
            })
            ->latest()
            ->get();
            
        // Load user notifications
        $notifications = $user->notifications()->with('sender')->latest()->limit(10)->get();
        $unreadCount = $user->unreadNotifications()->count();
            
        return view('auth.dashboard', compact('user', 'tickets', 'notifications', 'unreadCount'));
    }

    public function purchaseHistory()
    {
        $user = auth()->user();
        
        // Get tickets that have been marked as entered
        $enteredTickets = Ticket::with(['event', 'entryMarker'])
            ->where('user_id', $user->id)
            ->where('entry_status', 'entered')
            ->latest('entry_marked_at')
            ->get();
            
        return view('auth.purchase-history', compact('user', 'enteredTickets'));
    }


    public function edit()
    {
        $user = Auth::user();
        return view('auth.edit-profile', compact('user'));
    }

    
        public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|min:3',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|confirmed|min:6',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user->fill($request->only(['name', 'email', 'phone']));

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }

            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('dashboard')->with('success', 'Profile updated successfully.');
    }

    public function notifications()
    {
        $user = auth()->user();
        $notifications = $user->notifications()->with('sender')->latest()->paginate(20);
        
        return view('auth.notifications', compact('user', 'notifications'));
    }

    public function markNotificationAsRead(UserNotification $notification)
    {
        // Ensure the notification belongs to the authenticated user
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsAsRead()
    {
        auth()->user()->unreadNotifications()->update([
            'is_read' => true,
            'read_at' => now()
        ]);

        return response()->json(['success' => true, 'message' => 'All notifications marked as read']);
    }
}
