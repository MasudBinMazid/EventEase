<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('events', function (Blueprint $table) {
            if (!Schema::hasColumn('events', 'banner')) {
                $table->string('banner')->nullable();
            }
            if (!Schema::hasColumn('events', 'venue')) {
                $table->string('venue')->nullable();
            }
            if (!Schema::hasColumn('events', 'price')) {
                $table->decimal('price', 10, 2)->default(0);
            }
            if (!Schema::hasColumn('events', 'purchase_option')) {
                $table->enum('purchase_option', ['pay_now', 'pay_later', 'both'])->default('both');
            }
        });
    }

    public function down(): void
    {
        Schema::table('events', function (Blueprint $table) {
            // drop only if present (safe on older MySQL too)
            if (Schema::hasColumn('events', 'banner')) $table->dropColumn('banner');
            if (Schema::hasColumn('events', 'venue')) $table->dropColumn('venue');
            if (Schema::hasColumn('events', 'price')) $table->dropColumn('price');
            if (Schema::hasColumn('events', 'purchase_option')) $table->dropColumn('purchase_option');
        });
    }
};
