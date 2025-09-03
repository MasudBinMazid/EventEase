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
            // Add styling columns - removed 'after' clauses for better compatibility
            $table->string('bg_color', 7)->default('#f59e0b'); // Background color (hex)
            $table->string('text_color', 7)->default('#ffffff'); // Text color (hex)
            $table->string('font_family')->default('Inter, sans-serif'); // Font family
            $table->integer('font_size')->default(16); // Font size in pixels
            $table->string('font_weight')->default('500'); // Font weight (normal, bold, 100-900)
            $table->string('text_style')->default('normal'); // Text style (normal, italic)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notices', function (Blueprint $table) {
            $table->dropColumn([
                'bg_color',
                'text_color', 
                'font_family',
                'font_size',
                'font_weight',
                'text_style'
            ]);
        });
    }
};
