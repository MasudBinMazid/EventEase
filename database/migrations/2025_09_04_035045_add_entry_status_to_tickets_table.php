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
            $table->enum('entry_status', ['not_entered', 'entered'])->default('not_entered')->after('payment_status');
            $table->timestamp('entry_marked_at')->nullable()->after('entry_status');
            $table->foreignId('entry_marked_by')->nullable()->constrained('users')->nullOnDelete()->after('entry_marked_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropForeign(['entry_marked_by']);
            $table->dropColumn(['entry_status', 'entry_marked_at', 'entry_marked_by']);
        });
    }
};
