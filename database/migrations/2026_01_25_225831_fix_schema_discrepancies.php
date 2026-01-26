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
        // 1. Fix Purchases Table
        if (Schema::hasTable('purchases')) {
            Schema::table('purchases', function (Blueprint $table) {
                if (!Schema::hasColumn('purchases', 'subtotal')) {
                    $table->decimal('subtotal', 15, 2)->default(0)->after('purchase_date');
                }
                if (!Schema::hasColumn('purchases', 'tax')) {
                    $table->decimal('tax', 15, 2)->default(0)->after('subtotal');
                }
                if (!Schema::hasColumn('purchases', 'discount')) {
                    $table->decimal('discount', 15, 2)->default(0)->after('tax');
                }
                if (!Schema::hasColumn('purchases', 'total_amount')) {
                    $table->decimal('total_amount', 15, 2)->default(0)->after('discount');
                }
            });
        }

        // 2. Ensure Notifications Table exists
        if (!Schema::hasTable('notifications')) {
            Schema::create('notifications', function (Blueprint $table) {
                $table->id();
                $table->string('type'); // 'stock_low', 'spk_pending', etc
                $table->string('title');
                $table->text('message');
                $table->string('link')->nullable(); // URL to redirect
                $table->boolean('is_read')->default(false);
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // If null, for all/role specific? For now assume specific
                $table->string('role')->nullable(); // 'owner', 'operasional', etc (if user_id null)
                $table->json('data')->nullable(); // Extra data
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        // No safe reverse as we don't know state
    }
};
