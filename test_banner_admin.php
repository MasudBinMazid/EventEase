<?php
// Quick test to verify the banner admin page can load
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TESTING BANNER ADMIN CONTROLLER ===\n\n";

try {
    $controller = new \App\Http\Controllers\Admin\FeatureBannerController();
    
    // Mock request for index
    $response = $controller->index();
    echo "✅ Index method executed successfully\n";
    echo "View name: " . $response->getName() . "\n";
    
    // Check view data
    $banners = $response->getData()['banners'];
    echo "Banners passed to view: " . $banners->count() . "\n\n";
    
    foreach ($banners as $banner) {
        echo "Banner: {$banner->title}\n";
        echo "  Image: {$banner->image}\n";
        echo "  Link: " . ($banner->link ?: 'None') . "\n";
        echo "  Active: " . ($banner->is_active ? 'Yes' : 'No') . "\n";
        echo "  Order: {$banner->sort_order}\n\n";
    }
    
    echo "✅ Banner admin controller is fully functional!\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
