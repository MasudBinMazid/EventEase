<?php

// Test script to simulate payment cancellation flow
require 'vendor/autoload.php';

echo "=== Payment Cancellation Flow Test ===\n\n";

// Start with testing if we can create a temp transaction
try {
    $app = require 'bootstrap/app.php';
    $app->boot();
    
    // Create a temporary transaction to test with
    $tempTransaction = new \App\Models\TempTransaction();
    $tempTransaction->transaction_id = 'TEST_CANCEL_' . time();
    $tempTransaction->user_id = 1; // Assuming user ID 1 exists
    $tempTransaction->event_id = 1; // Assuming event ID 1 exists
    $tempTransaction->amount = 500.00;
    $tempTransaction->status = 'processing';
    $tempTransaction->data = [
        'event_id' => 1,
        'quantity' => 2,
        'total_amount' => 500.00,
        'user_id' => 1,
        'product_name' => 'Test Event Tickets'
    ];
    
    $tempTransaction->save();
    
    echo "✓ Created test transaction: {$tempTransaction->transaction_id}\n";
    
    // Test the cancellation URL
    $cancelUrl = route('sslcommerz.cancel');
    echo "✓ Cancel URL: $cancelUrl\n";
    
    // Test the cancelled page URL
    $cancelledPageUrl = route('payment.cancelled');
    echo "✓ Cancelled page URL: $cancelledPageUrl\n";
    
    echo "\n=== Test URLs ===\n";
    echo "To test cancellation flow:\n";
    echo "1. POST to: $cancelUrl\n";
    echo "   With data: tran_id={$tempTransaction->transaction_id}\n";
    echo "2. Should redirect to: $cancelledPageUrl\n";
    echo "3. Page should show cancellation message\n\n";
    
    // Cleanup
    $tempTransaction->delete();
    echo "✓ Cleaned up test transaction\n";
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Instructions for Testing ===\n";
echo "1. Go through a payment process with SSLCommerz\n";
echo "2. When the payment gateway loads, click Cancel\n";
echo "3. You should be redirected to /payment/cancelled page\n";
echo "4. User should remain logged in\n";
echo "5. Page should show cancellation message with retry options\n";
