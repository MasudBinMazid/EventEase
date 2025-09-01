<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('temp_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->integer('quantity');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->json('data')->nullable();
            $table->timestamps();
            
            $table->index(['transaction_id', 'status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_transactions');
    }
};
