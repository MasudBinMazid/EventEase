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
        Schema::table('tickets', function (Blueprint $table) {
            // SSLCommerz specific fields - only add if they don't exist
            if (!Schema::hasColumn('tickets', 'sslcommerz_val_id')) {
                $table->string('sslcommerz_val_id')->nullable()->after('payment_txn_id');
            }
            if (!Schema::hasColumn('tickets', 'sslcommerz_bank_tran_id')) {
                $table->string('sslcommerz_bank_tran_id')->nullable()->after('payment_txn_id');
            }
            if (!Schema::hasColumn('tickets', 'sslcommerz_card_type')) {
                $table->string('sslcommerz_card_type')->nullable()->after('payment_txn_id');
            }
            if (!Schema::hasColumn('tickets', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('payment_txn_id');
            }
            if (!Schema::hasColumn('tickets', 'payment_verified_at')) {
                $table->timestamp('payment_verified_at')->nullable()->after('payment_txn_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn([
                'sslcommerz_val_id',
                'sslcommerz_bank_tran_id',
                'sslcommerz_card_type',
                'payment_method',
                'payment_verified_at'
            ]);
        });
    }
};
