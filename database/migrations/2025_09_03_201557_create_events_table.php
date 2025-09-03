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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('banner_path')->nullable();
            $table->string('location')->nullable();
            $table->string('venue')->nullable();
            $table->dateTime('starts_at')->index();
            $table->dateTime('ends_at')->nullable();
            $table->integer('capacity')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->boolean('allow_pay_later')->default(true);
            $table->string('banner')->nullable();
            $table->enum('purchase_option', ['pay_now', 'pay_later', 'both'])->default('both');
            $table->enum('event_type', ['free', 'paid'])->default('free');
            $table->enum('event_status', ['available', 'limited_sell', 'sold_out'])->default('available');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending')->index();
            $table->unsignedBigInteger('approved_by')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('featured_on_home')->default(false);
            $table->boolean('visible_on_site')->default(true);
            $table->timestamps();

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            
            // Indexes
            $table->index(['event_type', 'event_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
