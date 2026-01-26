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
        Schema::table('spk_production_logs', function (Blueprint $table) {
            // Remove unused columns to simplify the log
            $table->dropColumn(['start_time', 'end_time', 'worker_name', 'notes']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spk_production_logs', function (Blueprint $table) {
            $table->time('start_time')->nullable()->after('work_date');
            $table->time('end_time')->nullable()->after('start_time');
            $table->string('worker_name')->nullable()->after('end_time');
            $table->text('notes')->nullable()->after('worker_name');
        });
    }
};
