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
        Schema::table('tickets', function (Blueprint $table) {
            // Reference to ticket type (nullable for backward compatibility)
            if (!Schema::hasColumn('tickets', 'ticket_type_id')) {
                $table->foreignId('ticket_type_id')->nullable()->after('event_id')->constrained('ticket_types')->nullOnDelete();
            }
            
            // Individual ticket price (may differ from total_amount due to quantity)
            if (!Schema::hasColumn('tickets', 'unit_price')) {
                $table->decimal('unit_price', 10, 2)->default(0)->after('total_amount');
            }
            
            // Add index (check if exists first)
            if (Schema::hasColumn('tickets', 'ticket_type_id')) {
                $indexes = Schema::getIndexes('tickets');
                $indexExists = false;
                foreach ($indexes as $index) {
                    if ($index['name'] === 'tickets_ticket_type_id_index') {
                        $indexExists = true;
                        break;
                    }
                }
                if (!$indexExists) {
                    $table->index('ticket_type_id');
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            if (Schema::hasColumn('tickets', 'ticket_type_id')) {
                $table->dropForeign(['ticket_type_id']);
                $table->dropIndex(['ticket_type_id']);
                $table->dropColumn('ticket_type_id');
            }
            if (Schema::hasColumn('tickets', 'unit_price')) {
                $table->dropColumn('unit_price');
            }
        });
    }
};
