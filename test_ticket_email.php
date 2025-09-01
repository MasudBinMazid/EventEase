<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

echo "=== Testing Ticket PDF Email Notification ===\n\n";

// Get the latest paid ticket for testing
$ticket = \App\Models\Ticket::with(['user', 'event'])
    ->where('payment_status', 'paid')
    ->latest()
    ->first();

if (!$ticket) {
    echo "❌ No paid tickets found in database. Creating a test scenario...\n";
    
    // Find a user and event to create a test ticket
    $user = \App\Models\User::first();
    $event = \App\Models\Event::first();
    
    if (!$user || !$event) {
        echo "❌ No users or events found. Please create some test data first.\n";
        exit(1);
    }
    
    echo "Creating test ticket for user: {$user->name} ({$user->email})\n";
    echo "Event: {$event->title}\n";
    
    // Create test ticket
    $ticket = \App\Models\Ticket::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'quantity' => 1,
        'total_amount' => $event->price,
        'payment_option' => 'pay_now',
        'payment_status' => 'paid',
        'ticket_code' => 'TKT-TEST-' . strtoupper(\Illuminate\Support\Str::random(6)),
        'ticket_number' => 'TN-' . time() . '-TEST',
        'payment_verified_at' => now(),
    ]);
    
    echo "✅ Test ticket created with ID: {$ticket->id}\n";
}

echo "Testing email notification for ticket:\n";
echo "- Ticket ID: {$ticket->id}\n";
echo "- Ticket Code: {$ticket->ticket_code}\n";
echo "- User: {$ticket->user->name} ({$ticket->user->email})\n";
echo "- Event: {$ticket->event->title}\n";
echo "- Payment Status: {$ticket->payment_status}\n\n";

try {
    echo "Sending email notification...\n";
    $ticket->user->notify(new \App\Notifications\TicketPdfNotification($ticket));
    echo "✅ Email notification sent successfully!\n";
    echo "📧 Check the email: {$ticket->user->email}\n";
} catch (\Exception $e) {
    echo "❌ Failed to send email: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\n=== Test completed ===\n";
