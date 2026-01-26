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
        Schema::create('spk_production_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_id')->constrained('spk')->cascadeOnDelete();
            $table->foreignId('spk_item_id')->constrained('spk_items')->cascadeOnDelete();
            $table->decimal('quantity', 15, 2);
            $table->date('work_date');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->string('worker_name')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();

            $table->index(['spk_id', 'work_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spk_production_logs');
    }
};
