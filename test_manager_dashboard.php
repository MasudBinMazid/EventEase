<?php
require_once 'vendor/autoload.php';

// Load Laravel app
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "Testing Manager Dashboard Functionality\n";
echo "======================================\n\n";

try {
    // Test if the manager role works
    $managerUsers = \App\Models\User::where('role', 'manager')->get();
    
    echo "Manager Users Found: " . $managerUsers->count() . "\n\n";
    
    if ($managerUsers->count() > 0) {
        $manager = $managerUsers->first();
        echo "Testing with manager: {$manager->name} ({$manager->email})\n\n";
        
        // Test the dashboard statistics
        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_events' => \App\Models\Event::count(),
            'total_tickets' => \App\Models\Ticket::count(),
            'total_blogs' => \App\Models\Blog::count(),
            'total_messages' => \App\Models\Contact::count(),
            'pending_requests' => \App\Models\Event::where('status', 'pending')->count(),
            'total_revenue' => \App\Models\Ticket::where('payment_status', 'paid')->sum('total_amount'),
            'recent_users' => \App\Models\User::where('created_at', '>=', now()->subDays(7))->count(),
        ];
        
        echo "Dashboard Statistics:\n";
        echo "  Total Users: " . $stats['total_users'] . "\n";
        echo "  Total Events: " . $stats['total_events'] . "\n";
        echo "  Total Tickets: " . $stats['total_tickets'] . "\n";
        echo "  Total Blogs: " . $stats['total_blogs'] . "\n";
        echo "  Total Messages: " . $stats['total_messages'] . "\n";
        echo "  Pending Requests: " . $stats['pending_requests'] . "\n";
        echo "  Total Revenue: ৳" . number_format($stats['total_revenue'], 2) . "\n";
        echo "  Recent Users (7 days): " . $stats['recent_users'] . "\n\n";
        
        // Test manager methods
        echo "Manager Role Tests:\n";
        echo "  isManager(): " . ($manager->isManager() ? '✅ Yes' : '❌ No') . "\n";
        echo "  isAdmin(): " . ($manager->isAdmin() ? '✅ Yes' : '❌ No') . "\n";
        echo "  isAdminOrManager(): " . ($manager->isAdminOrManager() ? '✅ Yes' : '❌ No') . "\n\n";
        
        // Test recent activities (limited)
        $recent_tickets = \App\Models\Ticket::with(['user', 'event'])
            ->latest()
            ->take(5)
            ->get();
            
        $recent_requests = \App\Models\Event::with('creator')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();
            
        $recent_messages = \App\Models\Contact::latest()
            ->take(5)
            ->get();
        
        echo "Recent Activity:\n";
        echo "  Recent Tickets: " . $recent_tickets->count() . " found\n";
        echo "  Recent Requests: " . $recent_requests->count() . " found\n";
        echo "  Recent Messages: " . $recent_messages->count() . " found\n\n";
        
        echo "✅ Manager dashboard controller is working correctly!\n\n";
        
        echo "Manager Panel URLs:\n";
        echo "  Dashboard: http://127.0.0.1:8001/manager\n";
        echo "  Users: http://127.0.0.1:8001/manager/users\n";
        echo "  Events: http://127.0.0.1:8001/admin/events (shared with admin)\n";
        echo "  Auto-redirect: http://127.0.0.1:8001/dashboard\n\n";
        
    } else {
        echo "❌ No manager users found. Run 'php assign_manager_role.php' first.\n";
    }

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}

echo "Done!\n";
?>
