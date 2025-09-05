<?php
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Notification Templates Debug ===\n";

// Check if table exists
try {
    $count = App\Models\NotificationTemplate::count();
    echo "✅ Templates count: $count\n";
    
    if ($count > 0) {
        echo "\n📝 Templates found:\n";
        $templates = App\Models\NotificationTemplate::select('id', 'name', 'category', 'type')->get();
        foreach ($templates as $template) {
            echo "- ID: {$template->id}, Name: {$template->name}, Category: {$template->category}, Type: {$template->type}\n";
        }
        
        echo "\n🎯 Active templates:\n";
        $activeTemplates = App\Models\NotificationTemplate::active()->get(['id', 'name', 'is_active']);
        echo "Active count: " . $activeTemplates->count() . "\n";
        
        echo "\n📂 Grouped by category:\n";
        $grouped = App\Models\NotificationTemplate::active()->get()->groupBy('category');
        foreach ($grouped as $category => $categoryTemplates) {
            echo "- {$category}: " . $categoryTemplates->count() . " templates\n";
        }
        
    } else {
        echo "❌ No templates found. Running seeder...\n";
        
        // Try to run seeder
        $seeder = new Database\Seeders\NotificationTemplateSeeder();
        $seeder->run();
        
        echo "✅ Seeder completed. New count: " . App\Models\NotificationTemplate::count() . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}

echo "\n=== Debug Complete ===\n";
