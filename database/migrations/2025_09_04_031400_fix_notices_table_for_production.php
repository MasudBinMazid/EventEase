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
        Schema::table('notices', function (Blueprint $table) {
            // Safely add fields that might be missing on production
            if (!Schema::hasColumn('notices', 'priority')) {
                $table->integer('priority')->default(0);
            }
            if (!Schema::hasColumn('notices', 'type')) {
                $table->string('type')->default('marquee');
            }
            if (!Schema::hasColumn('notices', 'is_marquee')) {
                $table->boolean('is_marquee')->default(true);
            }
            if (!Schema::hasColumn('notices', 'bg_color')) {
                $table->string('bg_color', 7)->default('#f59e0b');
            }
            if (!Schema::hasColumn('notices', 'text_color')) {
                $table->string('text_color', 7)->default('#ffffff');
            }
            if (!Schema::hasColumn('notices', 'font_family')) {
                $table->string('font_family')->default('Inter, sans-serif');
            }
            if (!Schema::hasColumn('notices', 'font_size')) {
                $table->integer('font_size')->default(16);
            }
            if (!Schema::hasColumn('notices', 'font_weight')) {
                $table->string('font_weight')->default('500');
            }
            if (!Schema::hasColumn('notices', 'text_style')) {
                $table->string('text_style')->default('normal');
            }
            
            // Remove the order column if it exists but priority doesn't (to avoid conflicts)
            if (Schema::hasColumn('notices', 'order') && !Schema::hasColumn('notices', 'priority')) {
                $table->renameColumn('order', 'priority');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            // Only drop columns if they exist
            if (Schema::hasColumn('notices', 'priority')) {
                $table->dropColumn('priority');
            }
            if (Schema::hasColumn('notices', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('notices', 'is_marquee')) {
                $table->dropColumn('is_marquee');
            }
        });
    }
};
