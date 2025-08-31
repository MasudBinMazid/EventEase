<?php

require __DIR__ . '/vendor/autoload.php';

// Initialize Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->boot();

use App\Models\Ticket;

echo "Testing ticket creation...\n";

try {
    $ticket = new Ticket();
    $ticket->user_id = 1;
    $ticket->event_id = 1;
    $ticket->quantity = 1;
    $ticket->total_amount = 10.00;
    $ticket->payment_option = 'pay_later';
    $ticket->payment_status = 'unpaid';
    $ticket->ticket_code = 'TEST-' . time();
    $ticket->ticket_number = 'TN-' . time() . '-' . strtoupper(bin2hex(random_bytes(2)));
    
    $ticket->save();
    
    echo "✓ Ticket created successfully!\n";
    echo "Ticket ID: " . $ticket->id . "\n";
    echo "Ticket Code: " . $ticket->ticket_code . "\n";
    echo "Ticket Number: " . $ticket->ticket_number . "\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

echo "Test completed.\n";
