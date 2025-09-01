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
            $table->string('bg_color', 7)->default('#f59e0b')->after('priority'); // Background color (hex)
            $table->string('text_color', 7)->default('#ffffff')->after('bg_color'); // Text color (hex)
            $table->string('font_family')->default('Inter, sans-serif')->after('text_color'); // Font family
            $table->integer('font_size')->default(16)->after('font_family'); // Font size in pixels
            $table->string('font_weight')->default('500')->after('font_size'); // Font weight (normal, bold, 100-900)
            $table->string('text_style')->default('normal')->after('font_weight'); // Text style (normal, italic)
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
