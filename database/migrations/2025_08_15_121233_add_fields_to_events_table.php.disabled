<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('events', function (Blueprint $table) {
            $table->string('banner_path')->nullable()->after('description');
            $table->decimal('price', 10, 2)->default(0)->after('location');
            $table->boolean('allow_pay_later')->default(true)->after('price');
        });
    }
    public function down(): void {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['banner_path', 'price', 'allow_pay_later']);
        });
    }
};
