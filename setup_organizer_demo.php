<?php
/**
 * Quick setup script for testing the Organizer Panel
 * This script creates demo data for testing the organizer functionality
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\User;
use App\Models\Event;
use Illuminate\Support\Facades\Hash;

echo "🚀 Setting up Organizer Panel Demo Data\n";
echo "======================================\n\n";

try {
    // Create or update organizer user
    $organizerEmail = 'organizer@example.com';
    $organizer = User::where('email', $organizerEmail)->first();
    
    if (!$organizer) {
        $organizer = User::create([
            'name' => 'Demo Organizer',
            'email' => $organizerEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'organizer',
        ]);
        echo "✅ Created new organizer user: {$organizerEmail}\n";
    } else {
        $organizer->update(['role' => 'organizer']);
        echo "✅ Updated existing user to organizer: {$organizerEmail}\n";
    }

    // Create demo events for the organizer
    $demoEvents = [
        [
            'title' => 'Tech Conference 2025',
            'description' => 'Annual technology conference featuring the latest innovations and networking opportunities.',
            'location' => 'Convention Center, Dhaka',
            'venue' => 'Main Auditorium',
            'starts_at' => now()->addDays(30),
            'ends_at' => now()->addDays(30)->addHours(8),
            'capacity' => 500,
            'price' => 1500.00,
            'allow_pay_later' => true,
        ],
        [
            'title' => 'Startup Pitch Night',
            'description' => 'Local entrepreneurs pitch their innovative ideas to investors and mentors.',
            'location' => 'Business Hub, Gulshan',
            'venue' => 'Conference Room A',
            'starts_at' => now()->addDays(15),
            'ends_at' => now()->addDays(15)->addHours(3),
            'capacity' => 100,
            'price' => 500.00,
            'allow_pay_later' => false,
        ],
        [
            'title' => 'Community Workshop: Web Development',
            'description' => 'Hands-on workshop covering modern web development techniques and best practices.',
            'location' => 'Online',
            'venue' => 'Zoom Platform',
            'starts_at' => now()->addDays(7),
            'ends_at' => now()->addDays(7)->addHours(4),
            'capacity' => 50,
            'price' => 0.00,
            'allow_pay_later' => false,
        ]
    ];

    $createdCount = 0;
    foreach ($demoEvents as $eventData) {
        $existingEvent = Event::where('title', $eventData['title'])
                            ->where('created_by', $organizer->id)
                            ->first();

        if (!$existingEvent) {
            $eventData['created_by'] = $organizer->id;
            $eventData['status'] = 'approved'; // Pre-approve for demo
            $eventData['approved_by'] = $organizer->id;
            $eventData['approved_at'] = now();
            $eventData['visible_on_site'] = true;

            Event::create($eventData);
            $createdCount++;
        }
    }

    echo "✅ Created {$createdCount} demo events for organizer\n";

    // Create regular user for comparison
    $regularEmail = 'user@example.com';
    $regularUser = User::where('email', $regularEmail)->first();
    
    if (!$regularUser) {
        User::create([
            'name' => 'Regular User',
            'email' => $regularEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('password123'),
            'role' => 'user',
        ]);
        echo "✅ Created regular user: {$regularEmail}\n";
    }

    echo "\n🎉 Demo setup completed successfully!\n\n";

    echo "📝 Login Credentials:\n";
    echo "Organizer Account:\n";
    echo "  Email: {$organizerEmail}\n";
    echo "  Password: password123\n";
    echo "  Panel URL: " . url('/organizer') . "\n\n";

    echo "Regular User Account:\n";
    echo "  Email: {$regularEmail}\n";
    echo "  Password: password123\n";
    echo "  Dashboard URL: " . url('/dashboard') . "\n\n";

    echo "🧪 Testing Instructions:\n";
    echo "1. Login with organizer credentials and visit /organizer\n";
    echo "2. Check that organizer can only see their own events\n";
    echo "3. Login with regular user to verify they can't access /organizer\n";
    echo "4. Create more events using the organizer account via /events/request/create\n\n";

    echo "🔒 Security Tests:\n";
    echo "- Try accessing /organizer while logged out (should redirect to login)\n";
    echo "- Try accessing /organizer as regular user (should show 403 error)\n";
    echo "- Try accessing other organizers' event details (should show 403 error)\n";

} catch (Exception $e) {
    echo "❌ Error setting up demo data: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
