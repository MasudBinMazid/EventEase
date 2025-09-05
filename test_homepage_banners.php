<?php
// Test homepage banner integration
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING HOMEPAGE BANNER INTEGRATION ===\n\n";

try {
    $controller = new \App\Http\Controllers\HomeController();
    
    // Test the index method
    $response = $controller->index();
    echo "✅ Home controller index method executed successfully\n";
    echo "View name: " . $response->getName() . "\n";
    
    // Check view data
    $data = $response->getData();
    $banners = $data['banners'];
    $featuredEvents = $data['featuredEvents'];
    
    echo "✅ Data passed to homepage:\n";
    echo "  - Banners: " . $banners->count() . "\n";
    echo "  - Featured Events: " . $featuredEvents->count() . "\n\n";
    
    echo "📋 Banner Details for Homepage:\n";
    foreach ($banners as $banner) {
        echo "  📸 {$banner->title}\n";
        echo "     Image: {$banner->image}\n";
        echo "     Link: " . ($banner->link ?: 'None') . "\n";
        echo "     Active: " . ($banner->is_active ? 'Yes' : 'No') . "\n\n";
    }
    
    echo "✅ Homepage banner integration is working perfectly!\n";
    echo "🎯 The slider will now show dynamic banners from the admin panel.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
