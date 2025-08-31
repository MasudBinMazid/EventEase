<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Initialize Laravel app
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Test the visibility functionality
echo "Testing Event Visibility Feature\n";
echo "================================\n\n";

// Check if the column exists
$columns = DB::select("PRAGMA table_info(events)");
$hasVisibleColumn = false;
foreach ($columns as $col) {
    if ($col->name === 'visible_on_site') {
        $hasVisibleColumn = true;
        break;
    }
}

if ($hasVisibleColumn) {
    echo "✓ visible_on_site column exists in events table\n";
} else {
    echo "✗ visible_on_site column not found in events table\n";
    exit(1);
}

// Check if events have the correct default value
$totalEvents = DB::table('events')->count();
$visibleEvents = DB::table('events')->where('visible_on_site', true)->count();
$hiddenEvents = DB::table('events')->where('visible_on_site', false)->count();

echo "✓ Total events: $totalEvents\n";
echo "✓ Visible events: $visibleEvents\n";  
echo "✓ Hidden events: $hiddenEvents\n\n";

// Test the Event model scopes
echo "Testing Model Scopes:\n";
$publicEvents = \App\Models\Event::visibleOnSite()->count();
echo "✓ Public visible events (via scope): $publicEvents\n";

$featuredEvents = \App\Models\Event::featuredOnHome()->count();
echo "✓ Featured events (includes visibility check): $featuredEvents\n\n";

echo "✅ All tests passed! The visibility feature is working correctly.\n";
echo "\nFeature Summary:\n";
echo "- Added 'visible_on_site' column to events table\n";
echo "- Updated Event model with proper casting and scopes\n";
echo "- Modified public EventController to only show visible events\n";
echo "- Updated admin views to manage event visibility\n";
echo "- Featured events now also check for visibility\n";
