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
        Schema::create('production_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spk_id')->constrained('spk')->onDelete('cascade');
            $table->foreignId('spk_item_id')->nullable()->constrained('spk_items')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('restrict');
            $table->enum('action', ['started', 'paused', 'resumed', 'completed', 'cancelled', 'production_entry']);
            $table->decimal('quantity_produced', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->json('consumed_materials')->nullable();
            $table->json('produced_items')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('production_logs');
    }
};
