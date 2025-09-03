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
        Schema::create('ticket_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., "Early Bird", "VIP", "General"
            $table->decimal('price', 10, 2);
            $table->text('description')->nullable(); // Description of this ticket type
            $table->integer('quantity_available')->nullable(); // null for unlimited
            $table->integer('quantity_sold')->default(0); // tracking sold tickets
            $table->enum('status', ['available', 'sold_out'])->default('available');
            $table->integer('sort_order')->default(0); // for ordering ticket types
            $table->timestamps();
            
            // Indexes
            $table->index(['event_id', 'status']);
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_types');
    }
};
