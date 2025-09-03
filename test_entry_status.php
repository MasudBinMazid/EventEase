<?php

require_once 'bootstrap/app.php';

use App\Models\Ticket;
use App\Models\User;
use App\Models\Event;

// Test the entry status functionality

echo "Testing Entry Status Feature\n";
echo "============================\n\n";

// Check if we have any tickets
$tickets = Ticket::with(['user', 'event'])->where('payment_status', 'paid')->limit(5)->get();

echo "Found " . count($tickets) . " paid tickets:\n\n";

foreach ($tickets as $ticket) {
    echo "Ticket: " . $ticket->ticket_code . "\n";
    echo "Event: " . $ticket->event->title . "\n";
    echo "Holder: " . $ticket->user->name . "\n";
    echo "Payment Status: " . $ticket->payment_status . "\n";
    echo "Entry Status: " . $ticket->entry_status . "\n";
    if ($ticket->entry_marked_at) {
        echo "Entered At: " . $ticket->entry_marked_at . "\n";
    }
    echo "---\n\n";
}

// Test marking a ticket as entered
if ($tickets->count() > 0) {
    $testTicket = $tickets->first();
    echo "Testing mark as entered for ticket: " . $testTicket->ticket_code . "\n";
    
    if ($testTicket->entry_status === 'not_entered') {
        // Mark as entered
        $testTicket->update([
            'entry_status' => 'entered',
            'entry_marked_at' => now(),
            'entry_marked_by' => 1, // Assuming user ID 1 exists
        ]);
        
        echo "✅ Ticket marked as entered successfully!\n";
        echo "Entry Status: " . $testTicket->fresh()->entry_status . "\n";
        echo "Entry Marked At: " . $testTicket->fresh()->entry_marked_at . "\n";
    } else {
        echo "⚠️ Ticket already marked as entered\n";
    }
}

echo "\n✅ Test completed!\n";
