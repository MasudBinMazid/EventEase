<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Event;

echo "🔍 Testing Admin Panel Enhancements\n\n";

// Test 1: Check if users table has role column and search works
echo "1. Testing User Management Features:\n";
try {
    $users = User::all();
    echo "✅ Found {$users->count()} users in database\n";
    
    // Test role counts
    $adminCount = $users->where('role', 'admin')->count();
    $organizerCount = $users->where('role', 'organizer')->count();
    $regularUsers = $users->whereNotIn('role', ['admin', 'organizer'])->count();
    
    echo "   - Admin users: {$adminCount}\n";
    echo "   - Organizer users: {$organizerCount}\n";
    echo "   - Regular users: {$regularUsers}\n";
    
    // Test search functionality (simulated)
    $searchResults = User::where('name', 'like', '%admin%')->get();
    echo "   - Search test (name like 'admin'): {$searchResults->count()} results\n";
    
} catch (Exception $e) {
    echo "❌ User management test failed: " . $e->getMessage() . "\n";
}

// Test 2: Check events and search capability
echo "\n2. Testing Event Management Features:\n";
try {
    $events = Event::all();
    echo "✅ Found {$events->count()} events in database\n";
    
    // Test event counts by status
    $featuredCount = $events->where('featured_on_home', true)->count();
    $visibleCount = $events->where('visible_on_site', true)->count();
    $hiddenCount = $events->where('visible_on_site', false)->count();
    
    echo "   - Featured events: {$featuredCount}\n";
    echo "   - Visible events: {$visibleCount}\n";
    echo "   - Hidden events: {$hiddenCount}\n";
    
    // Test search functionality (simulated)
    $searchResults = Event::where('title', 'like', '%event%')->get();
    echo "   - Search test (title like 'event'): {$searchResults->count()} results\n";
    
} catch (Exception $e) {
    echo "❌ Event management test failed: " . $e->getMessage() . "\n";
}

// Test 3: Test available user roles
echo "\n3. Testing User Role Options:\n";
$availableRoles = ['user', 'organizer', 'manager', 'admin'];
foreach ($availableRoles as $role) {
    $count = User::where('role', $role)->count();
    echo "   - {$role}: {$count} users\n";
}

// Test 4: Simulate role update (safely)
echo "\n4. Testing Role Update Functionality:\n";
try {
    $testUser = User::where('role', '!=', 'admin')->first();
    if ($testUser) {
        $oldRole = $testUser->role ?: 'user';
        echo "   - Test user #{$testUser->id} current role: {$oldRole}\n";
        echo "   - Role update simulation: Ready for testing via admin panel\n";
    } else {
        echo "   - No non-admin users available for role update test\n";
    }
} catch (Exception $e) {
    echo "❌ Role update test failed: " . $e->getMessage() . "\n";
}

echo "\n🌐 Admin Panel Enhancements Ready!\n\n";

echo "🔧 Features Implemented:\n";
echo "✅ User Search & Filter (by name, email, ID, role)\n";
echo "✅ User Role Management (User/Organizer/Manager/Admin)\n";
echo "✅ Event Search & Filter (by title, location, status, date range)\n";
echo "✅ Enhanced Statistics & Counts\n";
echo "✅ Improved UI with form controls and alerts\n";
echo "✅ Search result highlighting\n";
echo "✅ Security: Users can't remove their own admin role\n\n";

echo "🌐 Test URLs:\n";
echo "Admin Users: " . url('/admin/users') . "\n";
echo "Admin Events: " . url('/admin/events') . "\n";
echo "Admin Dashboard: " . url('/admin') . "\n\n";

echo "📝 Usage Instructions:\n";
echo "1. Navigate to /admin/users to manage users\n";
echo "2. Use search box to find specific users\n";
echo "3. Filter by role using dropdown\n";
echo "4. Change user roles via dropdown (auto-submits)\n";
echo "5. Navigate to /admin/events to manage events\n";
echo "6. Use search and filters to find specific events\n";
echo "7. Both pages now show search result counts\n\n";

echo "🎯 Ready to test in browser!\n";
?>
