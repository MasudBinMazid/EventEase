<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->boot();

echo "=== Fixing temp_transactions status column ===\n\n";

try {
    // Check current column definition
    $currentStatus = DB::select("SHOW COLUMNS FROM temp_transactions LIKE 'status'");
    echo "Current status column definition:\n";
    var_dump($currentStatus);
    echo "\n";
    
    // Update the enum to include 'cancelled'
    $result = DB::statement("ALTER TABLE temp_transactions MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending'");
    
    if ($result) {
        echo "✅ Successfully updated temp_transactions status column to include 'cancelled'\n";
        
        // Verify the change
        $updatedStatus = DB::select("SHOW COLUMNS FROM temp_transactions LIKE 'status'");
        echo "\nUpdated status column definition:\n";
        var_dump($updatedStatus);
        
    } else {
        echo "❌ Failed to update temp_transactions status column\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Test the payment cancellation now ===\n";
echo "The cancellation flow should work without database errors.\n";
