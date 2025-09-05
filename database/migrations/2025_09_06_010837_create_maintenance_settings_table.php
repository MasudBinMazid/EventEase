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
        Schema::create('maintenance_settings', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_enabled')->default(false);
            $table->string('title')->default('Site Under Maintenance');
            $table->text('message')->nullable(); // TEXT columns cannot have default values in MySQL
            $table->timestamp('estimated_completion')->nullable();
            $table->json('allowed_ips')->nullable(); // IPs that can access during maintenance
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();
            
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });

        // Insert default record
        \DB::table('maintenance_settings')->insert([
            'is_enabled' => false,
            'title' => 'Site Under Maintenance',
            'message' => 'We are currently performing maintenance on our website. We will be back online shortly!',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('maintenance_settings');
    }
};
