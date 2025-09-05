<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\NotificationTemplate;
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

        $users = $query->orderByDesc('id')->get(['id','name','email','role','created_at','last_login_at']);
        
        // Get notification templates for template selection
        try {
            $notificationTemplates = NotificationTemplate::active()->orderBy('category')->orderBy('name')->get();
            
            // If no active templates found, get all templates
            if ($notificationTemplates->isEmpty()) {
                $notificationTemplates = NotificationTemplate::orderBy('category')->orderBy('name')->get();
            }
            
            // If still empty, create some basic templates
            if ($notificationTemplates->isEmpty()) {
                $this->createBasicTemplates();
                $notificationTemplates = NotificationTemplate::orderBy('category')->orderBy('name')->get();
            }
            
        } catch (\Exception $e) {
            // Fallback to empty collection if there's any error
            $notificationTemplates = collect();
            \Log::error('Error loading notification templates: ' . $e->getMessage());
        }
        
        // Debug: Log template count
        \Log::info('Notification templates count: ' . $notificationTemplates->count());
        
        return view('admin.users.index', compact('users', 'notificationTemplates'));
    }

    public function destroy(User $user)
    {
        // Prevent managers from deleting users
        if (auth()->user()->isManager()) {
            return back()->with('error', 'Managers cannot delete users. Contact an admin for this action.');
        }

        abort_if(auth()->id() === $user->id, 403);
        $user->delete();
        return back()->with('success', 'User deleted successfully.');
    }

    public function updateRole(Request $request, User $user)
    {
        // Prevent managers from changing user roles
        if (auth()->user()->isManager()) {
            return back()->with('error', 'Managers cannot change user roles. Contact an admin for this action.');
        }

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

    public function sendNotification(Request $request, User $user)
    {
        $request->validate([
            'title' => 'required_without:template_id|string|max:255',
            'message' => 'required_without:template_id|string|max:1000',
            'type' => 'required_without:template_id|in:general,urgent,announcement,reminder',
            'template_id' => 'nullable|exists:notification_templates,id',
            'variables' => 'nullable|array'
        ]);

        try {
            $title = $request->title;
            $message = $request->message;
            $type = $request->type;

            // If template is selected, use template data
            if ($request->filled('template_id')) {
                $template = NotificationTemplate::find($request->template_id);
                
                if ($template) {
                    $variables = $request->variables ?? [];
                    // Add user-specific variables
                    $variables['user_name'] = $user->name;
                    $variables['user_email'] = $user->email;
                    
                    $title = $template->renderTitle($variables);
                    $message = $template->renderMessage($variables);
                    $type = $template->type;
                    
                    // Increment template usage count
                    $template->incrementUsage();
                }
            }

            // Create the notification
            UserNotification::create([
                'user_id' => $user->id,
                'sender_id' => auth()->id(),
                'title' => $title,
                'message' => $message,
                'type' => $type,
                'is_read' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => "Notification sent successfully to {$user->name}!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send notification: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getUserDetails(User $user)
    {
        $user->load(['events', 'tickets']);
        
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->role,
                'created_at' => $user->created_at->format('M j, Y g:i A'),
                'updated_at' => $user->updated_at->diffForHumans(),
                'last_login_at' => $user->last_login_at ? $user->last_login_at->format('M j, Y g:i A') : 'Never logged in',
                'last_login_relative' => $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never',
                'email_verified' => !is_null($user->email_verified_at),
                'events_count' => $user->events->count(),
                'tickets_count' => $user->tickets->count(),
                'days_active' => $user->created_at->diffInDays(now())
            ]
        ]);
    }
    
    public function sendBulkNotification(Request $request)
    {
        $request->validate([
            'roles' => 'required|array|min:1',
            'roles.*' => 'in:user,organizer,manager,admin',
            'title' => 'required_without:template_id|string|max:255',
            'message' => 'required_without:template_id|string|max:1000',
            'type' => 'required_without:template_id|in:general,urgent,announcement,reminder',
            'template_id' => 'nullable|exists:notification_templates,id',
            'variables' => 'nullable|array'
        ]);

        try {
            // Get users based on selected roles
            $users = User::whereIn('role', $request->roles)->get();
            
            if ($users->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No users found with the selected roles'
                ], 400);
            }

            $title = $request->title;
            $message = $request->message;
            $type = $request->type;
            $template = null;

            // If template is selected, get template data
            if ($request->filled('template_id')) {
                $template = NotificationTemplate::find($request->template_id);
                
                if ($template) {
                    $variables = $request->variables ?? [];
                    $type = $template->type;
                    
                    // We'll render per user to include user-specific variables
                    // Increment template usage count
                    $template->incrementUsage();
                }
            }

            $notifications = [];
            $currentTime = now();
            
            // Prepare bulk notification data
            foreach ($users as $user) {
                if ($template) {
                    // Render template for each user with user-specific variables
                    $userVariables = array_merge($request->variables ?? [], [
                        'user_name' => $user->name,
                        'user_email' => $user->email
                    ]);
                    
                    $userTitle = $template->renderTitle($userVariables);
                    $userMessage = $template->renderMessage($userVariables);
                } else {
                    $userTitle = $title;
                    $userMessage = $message;
                }

                $notifications[] = [
                    'user_id' => $user->id,
                    'sender_id' => auth()->id(),
                    'title' => $userTitle,
                    'message' => $userMessage,
                    'type' => $type,
                    'is_read' => false,
                    'created_at' => $currentTime,
                    'updated_at' => $currentTime
                ];
            }

            // Bulk insert notifications
            UserNotification::insert($notifications);

            $roleNames = collect($request->roles)->map(function($role) {
                return ucfirst($role) . 's';
            })->join(', ');

            return response()->json([
                'success' => true,
                'message' => "Notification sent successfully to {$users->count()} users ({$roleNames})!"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send bulk notification: ' . $e->getMessage()
            ], 500);
        }
    }
    
    private function createBasicTemplates()
    {
        $basicTemplates = [
            [
                'name' => 'Welcome New User',
                'title' => 'Welcome to EventEase! 🎉',
                'message' => 'Hello {{user_name}}! Welcome to EventEase. We\'re excited to have you join our community!',
                'type' => 'announcement',
                'category' => 'welcome',
                'variables' => ['user_name'],
                'is_active' => true
            ],
            [
                'name' => 'Payment Reminder',
                'title' => 'Payment Reminder 💳',
                'message' => 'Hi {{user_name}}, this is a reminder about your pending payment for {{event_name}}.',
                'type' => 'reminder',
                'category' => 'payment',
                'variables' => ['user_name', 'event_name'],
                'is_active' => true
            ],
            [
                'name' => 'Site Maintenance',
                'title' => 'Scheduled Maintenance 🔧',
                'message' => 'We will be performing maintenance on {{maintenance_date}}. The site may be temporarily unavailable.',
                'type' => 'announcement',
                'category' => 'maintenance',
                'variables' => ['maintenance_date'],
                'is_active' => true
            ]
        ];
        
        foreach ($basicTemplates as $template) {
            NotificationTemplate::firstOrCreate(
                ['name' => $template['name']],
                $template
            );
        }
    }
}
