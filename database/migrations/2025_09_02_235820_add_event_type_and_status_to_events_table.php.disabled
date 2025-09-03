<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // Event type: free or paid (check if exists first)
            if (!Schema::hasColumn('events', 'event_type')) {
                $table->enum('event_type', ['free', 'paid'])->default('free')->after('price');
            }
            
            // Check if we have selling_status or event_status
            if (Schema::hasColumn('events', 'selling_status') && !Schema::hasColumn('events', 'event_status')) {
                // Rename selling_status to event_status and update enum values
                DB::statement("ALTER TABLE events CHANGE selling_status event_status ENUM('available', 'limited_sell', 'sold_out') NOT NULL DEFAULT 'available'");
            } elseif (!Schema::hasColumn('events', 'event_status')) {
                // Add new event_status column
                $table->enum('event_status', ['available', 'limited_sell', 'sold_out'])->default('available')->after('event_type');
            }
            
            // Add index for better performance (only if columns exist)
            if (Schema::hasColumn('events', 'event_type') && Schema::hasColumn('events', 'event_status')) {
                $indexes = Schema::getIndexes('events');
                $indexExists = false;
                foreach ($indexes as $index) {
                    if ($index['name'] === 'events_event_type_event_status_index') {
                        $indexExists = true;
                        break;
                    }
                }
                if (!$indexExists) {
                    $table->index(['event_type', 'event_status']);
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (Schema::hasColumn('events', 'event_type') && Schema::hasColumn('events', 'event_status')) {
                $table->dropIndex(['event_type', 'event_status']);
            }
            if (Schema::hasColumn('events', 'event_status')) {
                $table->dropColumn('event_status');
            }
            if (Schema::hasColumn('events', 'event_type')) {
                $table->dropColumn('event_type');
            }
        });
    }
};
