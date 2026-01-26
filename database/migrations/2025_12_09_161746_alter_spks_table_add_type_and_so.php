<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('spk', function (Blueprint $table) {
            $table->enum('spk_type', ['base', 'finishgood'])->default('finishgood')->after('spk_number');
            $table->unsignedBigInteger('special_order_id')->nullable()->after('uuid');
        });
    }

    public function down(): void
    {
        Schema::table('spk', function (Blueprint $table) {
            $table->dropColumn(['spk_type', 'special_order_id']);
        });
    }
};
