<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Generate UUID for existing suppliers
        $suppliers = DB::table('suppliers')->whereNull('uuid')->get();
        foreach ($suppliers as $supplier) {
            DB::table('suppliers')
                ->where('id', $supplier->id)
                ->update(['uuid' => Str::uuid()->toString()]);
        }

        // Make uuid unique after populating
        Schema::table('suppliers', function (Blueprint $table) {
            $table->unique('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropUnique(['uuid']);
            $table->dropColumn('uuid');
        });
    }
};
