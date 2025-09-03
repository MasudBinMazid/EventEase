<?php

/**
 * Production Notice Debug Script
 * Upload this file to your Laravel Cloud deployment and access it via browser
 * to diagnose the notice system issues.
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<h1>EventEase Notice System Debug</h1>";
echo "<pre>";

try {
    // Initialize Laravel
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
    
    echo "✓ Laravel bootstrapped successfully\n\n";
    
    // Import required classes
    $schemaFacade = 'Illuminate\Support\Facades\Schema';
    $dbFacade = 'Illuminate\Support\Facades\DB';
    $routeFacade = 'Illuminate\Support\Facades\Route';
    
    // 1. Check database connection
    echo "=== DATABASE CONNECTION ===\n";
    $pdo = \Illuminate\Support\Facades\DB::connection()->getPdo();
    echo "✓ Database connected successfully\n";
    echo "Database: " . \Illuminate\Support\Facades\DB::connection()->getDatabaseName() . "\n\n";
    
    // 2. Check if tables exist
    echo "=== TABLE EXISTENCE ===\n";
    $noticesTableExists = \Illuminate\Support\Facades\Schema::hasTable('notices');
    $noticeSettingsTableExists = \Illuminate\Support\Facades\Schema::hasTable('notice_settings');
    echo "notices table: " . ($noticesTableExists ? '✓ EXISTS' : '✗ MISSING') . "\n";
    echo "notice_settings table: " . ($noticeSettingsTableExists ? '✓ EXISTS' : '✗ MISSING') . "\n\n";
    
    if (!$noticesTableExists) {
        echo "❌ CRITICAL: notices table is missing!\n";
        echo "Run: php artisan migrate\n\n";
    }
    
    if (!$noticeSettingsTableExists) {
        echo "❌ CRITICAL: notice_settings table is missing!\n";
        echo "Run: php artisan migrate\n\n";
    }
    
    // 3. Check table structure
    if ($noticesTableExists) {
        echo "=== NOTICES TABLE STRUCTURE ===\n";
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('notices');
        $requiredColumns = [
            'id', 'title', 'content', 'is_active', 'priority', 'start_date', 
            'end_date', 'bg_color', 'text_color', 'font_family', 'font_size', 
            'font_weight', 'text_style', 'type', 'is_marquee', 'created_at', 'updated_at'
        ];
        
        echo "Current columns: " . implode(', ', $columns) . "\n\n";
        
        echo "Column check:\n";
        foreach ($requiredColumns as $col) {
            $exists = in_array($col, $columns);
            echo "- $col: " . ($exists ? '✓' : '✗ MISSING') . "\n";
        }
        echo "\n";
        
        // Check for 'order' column (from original migration)
        if (in_array('order', $columns) && !in_array('priority', $columns)) {
            echo "⚠️  WARNING: Found 'order' column but missing 'priority' column\n";
            echo "   This might cause issues. Consider running the fix migration.\n\n";
        }
    }
    
    // 4. Check models
    echo "=== MODEL TESTS ===\n";
    try {
        $notices = \App\Models\Notice::all();
        echo "✓ Notice model works - found " . $notices->count() . " notices\n";
    } catch (Exception $e) {
        echo "✗ Notice model failed: " . $e->getMessage() . "\n";
    }
    
    try {
        $settings = \App\Models\NoticeSettings::getSettings();
        echo "✓ NoticeSettings model works\n";
        echo "  Notice bar enabled: " . ($settings->is_enabled ? 'YES' : 'NO') . "\n";
    } catch (Exception $e) {
        echo "✗ NoticeSettings model failed: " . $e->getMessage() . "\n";
    }
    echo "\n";
    
    // 5. Check routes
    echo "=== ROUTE TESTS ===\n";
    $routes = [
        'admin.notices.index',
        'admin.notices.create', 
        'admin.notices.store',
        'admin.notices.settings'
    ];
    
    foreach ($routes as $routeName) {
        try {
            $url = \Illuminate\Support\Facades\Route::has($routeName) ? route($routeName) : 'Route not found';
            echo "✓ Route '$routeName': $url\n";
        } catch (Exception $e) {
            echo "✗ Route '$routeName' failed: " . $e->getMessage() . "\n";
        }
    }
    echo "\n";
    
    // 6. Check controller
    echo "=== CONTROLLER TEST ===\n";
    try {
        $controller = new \App\Http\Controllers\Admin\NoticeController();
        echo "✓ NoticeController instantiated successfully\n";
        
        // Try to call index method
        $response = $controller->index();
        echo "✓ NoticeController::index() executed successfully\n";
        echo "  View: " . $response->getName() . "\n";
        
    } catch (Exception $e) {
        echo "✗ NoticeController failed: " . $e->getMessage() . "\n";
        echo "  File: " . $e->getFile() . ":" . $e->getLine() . "\n";
        echo "  This is likely the cause of your 500 error!\n";
    }
    echo "\n";
    
    // 7. Check view files
    echo "=== VIEW FILES ===\n";
    $viewFiles = [
        'resources/views/admin/notices/index.blade.php',
        'resources/views/admin/notices/create.blade.php',
        'resources/views/components/notice-bar.blade.php'
    ];
    
    foreach ($viewFiles as $viewFile) {
        if (file_exists($viewFile)) {
            echo "✓ $viewFile exists\n";
        } else {
            echo "✗ $viewFile missing\n";
        }
    }
    echo "\n";
    
    echo "=== RECOMMENDATIONS ===\n";
    echo "1. If any tables are missing, run: php artisan migrate\n";
    echo "2. If columns are missing, run the fix migration\n"; 
    echo "3. If controller fails, check the exact error message above\n";
    echo "4. Clear caches: php artisan config:clear && php artisan route:clear && php artisan view:clear\n";
    echo "5. Check Laravel logs in storage/logs/laravel.log\n\n";
    
} catch (Exception $e) {
    echo "❌ FATAL ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}

echo "</pre>";
echo "<p><strong>Debug completed at:</strong> " . date('Y-m-d H:i:s') . "</p>";
?>
