<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('payment_txn_id', 100)->nullable()->after('payment_status');
            $table->string('payer_number', 30)->nullable()->after('payment_txn_id');
            $table->string('payment_proof_path')->nullable()->after('payer_number');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_proof_path');
            $table->foreignId('payment_verified_by')->nullable()->constrained('users')->nullOnDelete()->after('payment_verified_at');
        });
    }

    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropConstrainedForeignId('payment_verified_by');
            $table->dropColumn(['payment_txn_id','payer_number','payment_proof_path','payment_verified_at']);
        });
    }
};
