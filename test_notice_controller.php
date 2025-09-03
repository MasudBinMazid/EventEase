<?php

// Test Notice Controller functionality
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Http\Controllers\Admin\NoticeController;
use Illuminate\Http\Request;
use App\Models\Notice;
use App\Models\NoticeSettings;

echo "=== Testing Notice Controller ===\n";

try {
    // Test 1: Create controller instance
    $controller = new NoticeController();
    echo "✓ NoticeController instantiated successfully\n";
    
    // Test 2: Test index method
    $response = $controller->index();
    echo "✓ Index method executed successfully\n";
    echo "View name: " . $response->getName() . "\n";
    
    // Test 3: Check if notices can be retrieved
    $notices = Notice::all();
    echo "✓ Found " . $notices->count() . " notices in database\n";
    
    // Test 4: Check settings
    $settings = NoticeSettings::getSettings();
    echo "✓ Settings retrieved successfully\n";
    echo "Notice bar enabled: " . ($settings->is_enabled ? 'YES' : 'NO') . "\n";
    
    // Test 5: Try create method
    $createResponse = $controller->create();
    echo "✓ Create method executed successfully\n";
    echo "Create view name: " . $createResponse->getName() . "\n";
    
    echo "\n=== All tests passed! ===\n";
    echo "The issue might be specific to the production environment.\n";
    
} catch (Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}
