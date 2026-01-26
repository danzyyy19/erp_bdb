<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('delivery_notes', function (Blueprint $table) {
            // Add special_order_id for SO-based Surat Jalan
            $table->foreignId('special_order_id')
                ->nullable()
                ->after('spk_id')
                ->constrained('special_orders')
                ->nullOnDelete();

            // Make spk_id nullable since SJ is now primarily SO-based
            $table->foreignId('spk_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_notes', function (Blueprint $table) {
            $table->dropForeign(['special_order_id']);
            $table->dropColumn('special_order_id');
        });
    }
};
