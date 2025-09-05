<?php

// Quick fix for the temp_transactions status enum issue
echo "Fixing temp_transactions status column...\n";

$host = 'localhost';
$dbname = 'eventease'; // Adjust this to your database name
$username = 'root';   // Adjust this to your database username  
$password = '';       // Adjust this to your database password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add 'cancelled' to the enum values
    $sql = "ALTER TABLE temp_transactions MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending'";
    
    $result = $pdo->exec($sql);
    
    echo "✅ SUCCESS: Added 'cancelled' status to temp_transactions table\n";
    echo "The payment cancellation should now work without database errors.\n";
    
} catch (PDOException $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "\nPlease run this SQL manually in your database:\n";
    echo "ALTER TABLE temp_transactions MODIFY COLUMN status ENUM('pending', 'processing', 'completed', 'failed', 'cancelled') DEFAULT 'pending';\n";
}
?>
