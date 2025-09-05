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
        Schema::create('feature_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('image'); // Path to banner image
            $table->string('link')->nullable(); // Banner click link
            $table->boolean('is_active')->default(true); // Show/hide banner
            $table->integer('sort_order')->default(0); // Display order
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feature_banners');
    }
};
