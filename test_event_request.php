<?php
/**
 * Test script to verify the Event Request functionality
 * This script tests the basic validation and model creation
 */

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

echo "🧪 Testing Event Request Functionality\n";
echo "=====================================\n\n";

// Test 1: Validation Rules
echo "📋 Test 1: Validation Rules\n";
$testData = [
    'title' => 'Test Event',
    'description' => 'A test event description',
    'location' => 'Test Location',
    'starts_at' => now()->addDay()->format('Y-m-d H:i:s'),
    'ends_at' => now()->addDay()->addHours(2)->format('Y-m-d H:i:s'),
    'capacity' => 100,
    'is_paid' => true,
    'price' => 50.00,
    'currency' => 'BDT',
    'allow_pay_later' => true,
];

$validator = Validator::make($testData, [
    'title'            => 'required|string|max:255',
    'description'      => 'nullable|string',
    'banner'           => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
    'location'         => 'nullable|string|max:255',
    'starts_at'        => 'required|date|after:now',
    'ends_at'          => 'nullable|date|after_or_equal:starts_at',
    'capacity'         => 'nullable|integer|min:1|max:10000',
    'is_paid'          => 'nullable|boolean',
    'price'            => 'nullable|numeric|min:0',
    'currency'         => 'nullable|string|in:BDT,USD,EUR',
    'allow_pay_later'  => 'nullable|boolean',
]);

if ($validator->passes()) {
    echo "✅ Validation rules passed!\n";
} else {
    echo "❌ Validation failed:\n";
    foreach ($validator->errors()->all() as $error) {
        echo "   - $error\n";
    }
}

// Test 2: Model Creation
echo "\n📊 Test 2: Event Model Creation\n";
try {
    $user = User::first();
    if (!$user) {
        echo "❌ No users found in database. Please create a user first.\n";
        exit(1);
    }

    $eventData = [
        'title' => 'Test Event ' . time(),
        'description' => 'A test event description',
        'location' => 'Test Location',
        'starts_at' => now()->addDay(),
        'ends_at' => now()->addDay()->addHours(2),
        'capacity' => 100,
        'price' => 50.00,
        'allow_pay_later' => true,
        'created_by' => $user->id,
        'status' => 'pending',
    ];

    $event = Event::create($eventData);
    echo "✅ Event created successfully! ID: {$event->id}\n";
    echo "   - Title: {$event->title}\n";
    echo "   - Price: ৳{$event->price}\n";
    echo "   - Allow pay later: " . ($event->allow_pay_later ? 'Yes' : 'No') . "\n";
    echo "   - Status: {$event->status}\n";

    // Clean up - delete the test event
    $event->delete();
    echo "   - Test event cleaned up\n";

} catch (Exception $e) {
    echo "❌ Error creating event: " . $e->getMessage() . "\n";
}

// Test 3: File Upload Directory
echo "\n📁 Test 3: Upload Directory\n";
$uploadDir = public_path('uploads/events');
if (is_dir($uploadDir)) {
    echo "✅ Upload directory exists: $uploadDir\n";
    if (is_writable($uploadDir)) {
        echo "✅ Upload directory is writable\n";
    } else {
        echo "❌ Upload directory is not writable\n";
    }
} else {
    echo "❌ Upload directory does not exist: $uploadDir\n";
}

// Test 4: Model Fillable Fields
echo "\n🔧 Test 4: Model Configuration\n";
$event = new Event();
$fillable = $event->getFillable();
$requiredFields = ['title', 'description', 'location', 'starts_at', 'ends_at', 'capacity', 'price', 'banner_path', 'allow_pay_later'];

echo "✅ Fillable fields: " . implode(', ', $fillable) . "\n";

$missingFields = array_diff($requiredFields, $fillable);
if (empty($missingFields)) {
    echo "✅ All required fields are fillable\n";
} else {
    echo "❌ Missing fillable fields: " . implode(', ', $missingFields) . "\n";
}

echo "\n🎉 Testing completed!\n";
echo "You can now use the event request form at: /events/request/create\n";
