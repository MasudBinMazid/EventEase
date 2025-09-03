<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('events', function (Blueprint $table) {
            $table->enum('status', ['pending','approved','rejected'])->default('pending')->index()->after('created_by');
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
            $table->timestamp('approved_at')->nullable()->after('approved_by');

            $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void {
        Schema::table('events', function (Blueprint $table) {
            $table->dropForeign(['approved_by']);
            $table->dropColumn(['status','approved_by','approved_at']);
        });
    }
};
