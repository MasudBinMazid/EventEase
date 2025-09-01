<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

echo "=== Payment Success Page Debug ===\n\n";

// Check tickets
$totalTickets = \App\Models\Ticket::count();
echo "Total tickets in database: {$totalTickets}\n\n";

if ($totalTickets > 0) {
    echo "Recent tickets:\n";
    $tickets = \App\Models\Ticket::with('event', 'user')->latest()->take(5)->get();
    foreach ($tickets as $ticket) {
        echo "- ID: {$ticket->id}, Code: {$ticket->ticket_code}, ";
        echo "Status: {$ticket->payment_status}, User: {$ticket->user->name} ({$ticket->user_id})\n";
        echo "  Event: {$ticket->event->title}\n";
        echo "  Amount: ৳{$ticket->total_amount}, Created: {$ticket->created_at}\n\n";
    }
    
    $firstTicket = $tickets->first();
    echo "=== Test URL ===\n";
    echo "You can test the success page with:\n";
    echo "http://127.0.0.1:8000/payment/test-success/{$firstTicket->id}\n";
    echo "(Make sure you're logged in as user ID: {$firstTicket->user_id})\n\n";
} else {
    echo "No tickets found. Create a test ticket first.\n\n";
}

echo "=== CSRF Exception Status ===\n";
$bootstrapContent = file_get_contents('bootstrap/app.php');
if (strpos($bootstrapContent, 'validateCsrfTokens') !== false) {
    echo "✅ CSRF exceptions configured in bootstrap/app.php\n";
} else {
    echo "❌ CSRF exceptions NOT configured\n";
}

echo "\n=== Next Steps ===\n";
echo "1. Make sure you're logged in as a valid user\n";
echo "2. Try the test URL above\n";
echo "3. Or create a new event and purchase a ticket\n";
echo "4. Check logs: storage/logs/laravel.log\n";
