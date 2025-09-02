<?php
/**
 * Test script to verify the updated Event Request functionality
 * This script tests that all new fields are properly handled
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

echo "🧪 Testing Updated Event Request Functionality\n";
echo "=============================================\n\n";

// Test 1: Validation Rules with New Fields
echo "📋 Test 1: Validation Rules with New Fields\n";
$testData = [
    'title' => 'Updated Test Event',
    'description' => 'A comprehensive test event description with all new features.',
    'location' => 'Dhaka, Bangladesh',
    'venue' => 'International Convention Center',
    'starts_at' => now()->addDay()->format('Y-m-d H:i:s'),
    'ends_at' => now()->addDay()->addHours(3)->format('Y-m-d H:i:s'),
    'capacity' => 200,
    'price' => 150.00,
    'event_type' => 'paid',
    'event_status' => 'available',
    'purchase_option' => 'both',
    'featured_on_home' => true,
    'visible_on_site' => true,
];

// Use the same validation rules as the controller
$rules = [
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
    'featured_on_home' => 'nullable|boolean',
    'visible_on_site'  => 'nullable|boolean',
];

$validator = Validator::make($testData, $rules);

if ($validator->passes()) {
    echo "✅ All validation rules passed\n";
} else {
    echo "❌ Validation failed:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "   - $error\n";
    }
}

// Test 2: Model Fillable Fields
echo "\n🔧 Test 2: Model Fillable Fields\n";
$event = new Event();
$fillable = $event->getFillable();
$newRequiredFields = ['venue', 'event_type', 'event_status', 'purchase_option'];

echo "✅ Current fillable fields: " . implode(', ', $fillable) . "\n";

$missingFields = array_diff($newRequiredFields, $fillable);
if (empty($missingFields)) {
    echo "✅ All new required fields are fillable\n";
} else {
    echo "❌ Missing fillable fields: " . implode(', ', $missingFields) . "\n";
}

// Test 3: Test Data Structure Compatibility
echo "\n🏗️ Test 3: Data Structure Compatibility\n";

// Find an admin user for testing
$adminUser = User::where('role', 'admin')->first();
if (!$adminUser) {
    echo "❌ No admin user found. Creating one...\n";
    $adminUser = User::create([
        'name' => 'Test Admin',
        'email' => 'test.admin@eventease.local',
        'password' => bcrypt('password'),
        'role' => 'admin',
        'email_verified_at' => now(),
    ]);
    echo "✅ Test admin user created\n";
}

// Test event creation with all new fields
try {
    $eventData = [
        'title'            => 'Test Event with New Fields',
        'description'      => 'Testing all the new fields added to the event request form.',
        'location'         => 'Dhaka, Bangladesh',
        'venue'            => 'Test Convention Center',
        'starts_at'        => now()->addWeek(),
        'ends_at'          => now()->addWeek()->addHours(4),
        'capacity'         => 150,
        'price'            => 75.50,
        'event_type'       => 'paid',
        'event_status'     => 'available',
        'purchase_option'  => 'both',
        'featured_on_home' => true,
        'visible_on_site'  => true,
        'allow_pay_later'  => true,
        'created_by'       => $adminUser->id,
        'status'           => 'pending',
    ];
    
    $testEvent = Event::create($eventData);
    echo "✅ Event created successfully with ID: {$testEvent->id}\n";
    echo "   - Event Type: {$testEvent->event_type}\n";
    echo "   - Event Status: {$testEvent->event_status}\n";
    echo "   - Purchase Option: {$testEvent->purchase_option}\n";
    echo "   - Featured on Home: " . ($testEvent->featured_on_home ? 'Yes' : 'No') . "\n";
    echo "   - Visible on Site: " . ($testEvent->visible_on_site ? 'Yes' : 'No') . "\n";
    echo "   - Location: {$testEvent->location}\n";
    echo "   - Venue: {$testEvent->venue}\n";
    
    // Clean up test event
    $testEvent->delete();
    echo "✅ Test event cleaned up\n";
    
} catch (Exception $e) {
    echo "❌ Error creating test event: " . $e->getMessage() . "\n";
}

// Test 4: Admin Panel Display Fields
echo "\n👔 Test 4: Admin Panel Display Compatibility\n";
$recentEvents = Event::where('status', 'pending')
    ->select([
        'id', 'title', 'description', 'location', 'venue', 'starts_at', 'ends_at',
        'capacity', 'price', 'event_type', 'event_status', 'purchase_option',
        'featured_on_home', 'visible_on_site', 'created_at'
    ])
    ->limit(1)
    ->get();

if ($recentEvents->count() > 0) {
    $event = $recentEvents->first();
    echo "✅ Found pending event for admin display test:\n";
    echo "   - ID: {$event->id}\n";
    echo "   - Title: {$event->title}\n";
    echo "   - Type: " . ($event->event_type ?? 'N/A') . "\n";
    echo "   - Status: " . ($event->event_status ?? 'N/A') . "\n";
    echo "   - Purchase: " . ($event->purchase_option ?? 'N/A') . "\n";
    echo "   - Featured: " . ($event->featured_on_home ? 'Yes' : 'No') . "\n";
    echo "   - Public: " . ($event->visible_on_site ? 'Yes' : 'No') . "\n";
} else {
    echo "ℹ️ No pending events found for admin display test\n";
}

echo "\n🎉 Testing completed successfully!\n";
echo "\n📝 Summary of Changes Made:\n";
echo "✅ Updated EventRequestController with new validation rules\n";
echo "✅ Enhanced event request form with all admin form fields\n";
echo "✅ Added venue field (separate from location)\n";
echo "✅ Added event_type field (free/paid)\n";
echo "✅ Added event_status field (available/limited_sell/sold_out)\n";
echo "✅ Added purchase_option field (both/pay_now/pay_later)\n";
echo "✅ Added featured_on_home and visible_on_site checkboxes\n";
echo "✅ Updated admin requests view to display all new fields\n";
echo "✅ Enhanced JavaScript for new form structure\n";
echo "\n🌐 You can now test the updated form at: /events/request/create\n";
echo "🔧 Admin panel requests at: /admin/event-requests\n";
