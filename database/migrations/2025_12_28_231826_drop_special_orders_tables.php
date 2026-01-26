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
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        // Drop special_order_id from SPK table first
        try {
            DB::statement('ALTER TABLE spk DROP COLUMN IF EXISTS special_order_id');
        } catch (\Exception $e) {
            // Column might not exist
        }

        // Drop special_order_items table
        Schema::dropIfExists('special_order_items');

        // Drop special_orders table
        Schema::dropIfExists('special_orders');

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate special_orders table
        Schema::create('special_orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('so_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('created_by')->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->date('order_date');
            $table->date('delivery_date')->nullable();
            $table->decimal('total', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected', 'in_production', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });

        // Recreate special_order_items table
        Schema::create('special_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('special_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained();
            $table->decimal('quantity', 15, 4);
            $table->string('unit', 50);
            $table->decimal('unit_price', 15, 2)->default(0);
            $table->decimal('total_price', 15, 2)->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // Add back special_order_id to SPK table
        Schema::table('spk', function (Blueprint $table) {
            $table->unsignedBigInteger('special_order_id')->nullable()->after('spk_type');
        });
    }
};
