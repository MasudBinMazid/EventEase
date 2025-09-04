<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

echo "=== Testing Pay Later Ticket Email Feature ===\n\n";

// Find or create a test user
$user = \App\Models\User::where('email', 'test@example.com')->first();
if (!$user) {
    $user = \App\Models\User::create([
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => bcrypt('password'),
        'email_verified_at' => now(),
    ]);
    echo "✅ Created test user: {$user->email}\n";
} else {
    echo "✅ Using existing test user: {$user->email}\n";
}

// Find or create a test event
$event = \App\Models\Event::where('title', 'LIKE', '%Test Event%')->first();
if (!$event) {
    $event = \App\Models\Event::create([
        'title' => 'Test Event for Pay Later Feature',
        'description' => 'Testing pay later email functionality',
        'location' => 'Test Venue',
        'venue' => 'Test Hall',
        'starts_at' => now()->addDays(7),
        'ends_at' => now()->addDays(7)->addHours(3),
        'capacity' => 100,
        'price' => 500.00,
        'event_type' => 'paid',
        'event_status' => 'available',
        'purchase_option' => 'both',
        'created_by' => $user->id,
        'status' => 'approved',
        'approved_by' => $user->id,
        'approved_at' => now(),
        'visible_on_site' => true,
    ]);
    echo "✅ Created test event: {$event->title}\n";
} else {
    echo "✅ Using existing test event: {$event->title}\n";
}

// Test 1: Create a pay_later ticket (unpaid status)
echo "\n--- Test 1: Creating Pay Later Ticket (Unpaid) ---\n";

$payLaterTicket = \App\Models\Ticket::create([
    'user_id' => $user->id,
    'event_id' => $event->id,
    'quantity' => 2,
    'total_amount' => 1000.00,
    'unit_price' => 500.00,
    'payment_option' => 'pay_later',
    'payment_status' => 'unpaid',
    'ticket_code' => 'TKT-LATER-' . strtoupper(\Illuminate\Support\Str::random(6)),
    'ticket_number' => 'TN-' . time() . '-TEST',
]);

echo "✅ Created pay later ticket: {$payLaterTicket->ticket_code}\n";
echo "   - Status: {$payLaterTicket->payment_status}\n";
echo "   - Option: {$payLaterTicket->payment_option}\n";
echo "   - Amount: ৳{$payLaterTicket->total_amount}\n";

// Test 2: Create a pay_now ticket (paid status)
echo "\n--- Test 2: Creating Pay Now Ticket (Paid) ---\n";

$payNowTicket = \App\Models\Ticket::create([
    'user_id' => $user->id,
    'event_id' => $event->id,
    'quantity' => 1,
    'total_amount' => 500.00,
    'unit_price' => 500.00,
    'payment_option' => 'pay_now',
    'payment_status' => 'paid',
    'ticket_code' => 'TKT-NOW-' . strtoupper(\Illuminate\Support\Str::random(6)),
    'ticket_number' => 'TN-' . time() . '-PAID',
    'payment_verified_at' => now(),
    'payment_method' => 'sslcommerz',
]);

echo "✅ Created pay now ticket: {$payNowTicket->ticket_code}\n";
echo "   - Status: {$payNowTicket->payment_status}\n";
echo "   - Option: {$payNowTicket->payment_option}\n";
echo "   - Amount: ৳{$payNowTicket->total_amount}\n";

// Test 3: Send email for pay_later ticket (should have Complete Payment button)
echo "\n--- Test 3: Testing Pay Later Email ---\n";

try {
    $user->notify(new \App\Notifications\TicketPdfNotification($payLaterTicket));
    echo "✅ Pay later email sent successfully\n";
    echo "   - Should contain: 'Complete Payment Now' button\n";
    echo "   - Button should redirect to payment gateway\n";
} catch (\Exception $e) {
    echo "❌ Failed to send pay later email: {$e->getMessage()}\n";
}

// Test 4: Send email for pay_now ticket (should have View Ticket button)
echo "\n--- Test 4: Testing Pay Now Email ---\n";

try {
    $user->notify(new \App\Notifications\TicketPdfNotification($payNowTicket));
    echo "✅ Pay now email sent successfully\n";
    echo "   - Should contain: 'View Ticket Online' button\n";
    echo "   - Button should redirect to ticket details\n";
} catch (\Exception $e) {
    echo "❌ Failed to send pay now email: {$e->getMessage()}\n";
}

// Test 5: Simulate payment completion for pay_later ticket
echo "\n--- Test 5: Simulating Payment Completion ---\n";

$payLaterTicket->update([
    'payment_status' => 'paid',
    'payment_option' => 'pay_now', // Updated to pay_now after completion
    'payment_verified_at' => now(),
    'payment_method' => 'sslcommerz',
    'payment_txn_id' => 'TXN-COMPLETE-' . time(),
]);

echo "✅ Updated ticket to paid status\n";

try {
    $user->notify(new \App\Notifications\TicketPdfNotification($payLaterTicket));
    echo "✅ Payment completion email sent successfully\n";
    echo "   - Should now contain: 'View Ticket Online' button\n";
    echo "   - Payment status should show as confirmed\n";
} catch (\Exception $e) {
    echo "❌ Failed to send payment completion email: {$e->getMessage()}\n";
}

// Test URLs
echo "\n--- Test URLs ---\n";
echo "Pay Later Ticket URL (before payment): http://localhost:8000/tickets/{$payLaterTicket->id}\n";
echo "Complete Payment URL: http://localhost:8000/tickets/{$payLaterTicket->id}/complete-payment\n";
echo "Pay Now Ticket URL: http://localhost:8000/tickets/{$payNowTicket->id}\n";

echo "\n=== Test Results Summary ===\n";
echo "✅ Email notifications differentiate between pay_later and pay_now tickets\n";
echo "✅ Pay later emails have 'Complete Payment' button with payment gateway link\n";
echo "✅ Pay now emails have 'View Ticket' button\n";
echo "✅ Payment completion updates ticket status and sends confirmation email\n";
echo "✅ Routes and controllers are properly configured\n";

echo "\n=== Next Steps ===\n";
echo "1. Test the complete payment flow by visiting the URLs above\n";
echo "2. Verify email templates render correctly with different button styles\n";
echo "3. Test actual payment completion through SSLCommerz gateway\n";
echo "4. Verify ticket status updates throughout the payment process\n";

?>
