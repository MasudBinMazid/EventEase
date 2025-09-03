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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ticket_type_id')->nullable()->constrained('ticket_types')->nullOnDelete();
            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->decimal('unit_price', 10, 2)->default(0);
            $table->enum('payment_option', ['pay_now','pay_later'])->default('pay_later');
            $table->enum('payment_status', ['unpaid','paid','cancelled'])->default('unpaid');
            $table->string('payment_txn_id', 100)->nullable();
            $table->string('payer_number', 30)->nullable();
            $table->string('payment_proof_path')->nullable();
            $table->timestamp('payment_verified_at')->nullable();
            $table->foreignId('payment_verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('sslcommerz_val_id')->nullable();
            $table->string('sslcommerz_bank_tran_id')->nullable();
            $table->string('sslcommerz_card_type')->nullable();
            $table->string('payment_method')->nullable();
            $table->string('ticket_code')->unique();
            $table->string('ticket_number')->unique()->nullable();
            $table->string('qr_path')->nullable(); // where QR png will be saved
            $table->timestamps();
            
            // Indexes
            $table->index('ticket_type_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
