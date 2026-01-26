<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('delivery_notes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('sj_number')->unique(); // SJ-YYYYMM-0001
            $table->foreignId('customer_id')->nullable()->constrained();
            $table->unsignedBigInteger('spk_id')->nullable();
            $table->unsignedBigInteger('invoice_id')->nullable();
            $table->foreignId('created_by')->constrained('users');

            $table->date('delivery_date');
            $table->string('driver_name')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('recipient_name')->nullable();
            $table->text('delivery_address');
            $table->text('notes')->nullable();

            $table->enum('status', ['pending', 'in_transit', 'delivered', 'returned'])
                ->default('pending');

            $table->timestamp('delivered_at')->nullable();
            $table->timestamps();
        });

        Schema::create('delivery_note_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('delivery_note_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->decimal('quantity', 10, 2);
            $table->string('unit', 20);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_note_items');
        Schema::dropIfExists('delivery_notes');
    }
};
