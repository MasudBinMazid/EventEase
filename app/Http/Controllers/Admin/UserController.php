<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%");
            });
        }

        // Filter by role
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderByDesc('id')->get(['id','name','email','role','created_at']);
        
        return view('admin.users.index', compact('users'));
    }

    public function destroy(User $user)
    {
        abort_if(auth()->id() === $user->id, 403);
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:user,admin,organizer,manager'
        ]);

        // Prevent users from removing their own admin role
        if (auth()->id() === $user->id && $user->role === 'admin' && $request->role !== 'admin') {
            return back()->with('error', 'You cannot remove your own admin privileges.');
        }

        $user->update(['role' => $request->role]);
        
        return back()->with('success', 'User role updated successfully.');
    }
}
