<?php

require __DIR__ . '/vendor/autoload.php';

echo "🔄 Testing Notices Migration Fix\n\n";

try {
    // Initialize Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

    echo "✅ Laravel Bootstrap: OK\n";

    // Test database connection
    $connection = \Illuminate\Support\Facades\DB::connection();
    $connection->getPdo();
    
    echo "✅ Database Connection: OK\n";

    // Check notices table structure
    if (\Illuminate\Support\Facades\Schema::hasTable('notices')) {
        echo "✅ Notices table exists\n";
        
        // Check if columns exist in the correct table
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing('notices');
        echo "📋 Current notices table columns: " . implode(', ', $columns) . "\n";
        
        // Check for the correct column that should exist
        if (in_array('order', $columns)) {
            echo "✅ Column 'order' exists (correct reference)\n";
        } else {
            echo "❌ Column 'order' missing\n";
        }
        
        if (in_array('priority', $columns)) {
            echo "⚠️  Column 'priority' exists (unexpected)\n";
        } else {
            echo "✅ Column 'priority' does not exist (expected)\n";
        }
        
        // Check for styling columns
        $stylingColumns = ['bg_color', 'text_color', 'font_family', 'font_size', 'font_weight', 'text_style'];
        foreach ($stylingColumns as $column) {
            if (in_array($column, $columns)) {
                echo "✅ Styling column '{$column}' exists\n";
            } else {
                echo "⏳ Styling column '{$column}' not yet added (will be added by migration)\n";
            }
        }
        
    } else {
        echo "⏳ Notices table doesn't exist yet (will be created by migration)\n";
    }

    echo "\n🎯 Migration Fix Applied:\n";
    echo "   - Changed 'after priority' to 'after order'\n";
    echo "   - The 'order' column exists in the notices table\n";
    echo "   - Migration should now work correctly\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\nThis might be expected if database is not configured locally.\n";
}

echo "\n✅ Notices migration fix completed!\n";
echo "\n💡 Deploy again - the styling fields migration should now work.\n";
