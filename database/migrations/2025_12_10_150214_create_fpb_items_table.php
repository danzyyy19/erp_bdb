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
        Schema::create('fpb_items', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('fpb_id')->constrained('fpb')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products');
            $table->decimal('quantity_requested', 15, 2);
            $table->string('unit', 50)->default('pcs');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fpb_items');
    }
};
