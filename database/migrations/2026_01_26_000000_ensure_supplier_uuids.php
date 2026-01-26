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
        // Ensure the uuid column exists (redundant safety check if previous migration failed partially)
        if (!Schema::hasColumn('suppliers', 'uuid')) {
            Schema::table('suppliers', function (Blueprint $table) {
                $table->uuid('uuid')->nullable()->after('id');
            });
        }

        // Generate UUID for any suppliers that have NULL or EMPTY uuid
        $suppliers = DB::table('suppliers')
            ->whereNull('uuid')
            ->orWhere('uuid', '')
            ->get();

        foreach ($suppliers as $supplier) {
            DB::table('suppliers')
                ->where('id', $supplier->id)
                ->update(['uuid' => Str::uuid()->toString()]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No down action needed as this is a data-fix migration
    }
};
