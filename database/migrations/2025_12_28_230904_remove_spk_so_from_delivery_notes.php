<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Use raw SQL to drop columns safely
        try {
            DB::statement('ALTER TABLE delivery_notes DROP COLUMN IF EXISTS spk_id');
        } catch (\Exception $e) {
            // Column might not exist, that's OK
        }

        try {
            DB::statement('ALTER TABLE delivery_notes DROP COLUMN IF EXISTS special_order_id');
        } catch (\Exception $e) {
            // Column might not exist, that's OK
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_notes', function (Blueprint $table) {
            if (!Schema::hasColumn('delivery_notes', 'spk_id')) {
                $table->unsignedBigInteger('spk_id')->nullable()->after('customer_id');
            }
            if (!Schema::hasColumn('delivery_notes', 'special_order_id')) {
                $table->unsignedBigInteger('special_order_id')->nullable()->after('spk_id');
            }
        });
    }
};
