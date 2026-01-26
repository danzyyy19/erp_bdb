<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Spk;
use App\Models\SpkItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class SpkSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        $users = User::all();
        $admin = $users->where('role', 'owner')->first() ?? $users->first();

        // Get products by category type
        $bahanBaku = Product::whereHas('category', fn($q) => $q->where('type', 'bahan_baku'))->get();
        $packaging = Product::whereHas('category', fn($q) => $q->where('type', 'packaging'))->get();
        $barangJadi = Product::whereHas('category', fn($q) => $q->where('type', 'barang_jadi'))->get();

        if ($barangJadi->isEmpty() || $bahanBaku->isEmpty()) {
            return;
        }

        // Track sequences per month to ensure uniqueness
        $sequences = [];

        // Create 20 SPKs
        for ($i = 0; $i < 20; $i++) {
            $status = $faker->randomElement(['pending', 'approved', 'in_progress', 'completed', 'rejected']);

            // Dates logic
            $createdAt = Carbon::now()->subDays($faker->numberBetween(1, 60));
            $productionDate = $createdAt->copy()->addDays($faker->numberBetween(1, 5));
            $deadline = $productionDate->copy()->addDays($faker->numberBetween(5, 15));
            $approvedAt = ($status !== 'pending') ? $createdAt->copy()->addHours($faker->numberBetween(1, 24)) : null;
            $completedAt = ($status === 'completed') ? $deadline->copy()->subDays($faker->numberBetween(0, 3)) : null;

            // Generate SPK Number manually based on created_at
            $monthKey = $createdAt->format('Ym');
            if (!isset($sequences[$monthKey])) {
                // Check DB for potential existing max
                $lastSpk = Spk::whereYear('created_at', $createdAt->year)
                    ->whereMonth('created_at', $createdAt->month)
                    ->orderBy('id', 'desc')
                    ->first();
                $sequences[$monthKey] = $lastSpk ? ((int) substr($lastSpk->spk_number, -4)) : 0;
            }
            $sequences[$monthKey]++;
            $spkNumber = sprintf('SPK-%s-%04d', $monthKey, $sequences[$monthKey]);

            $spk = Spk::create([
                'uuid' => Str::uuid()->toString(),
                'spk_number' => $spkNumber,
                'created_by' => $users->random()->id,
                'approved_by' => ($status !== 'pending') ? $admin->id : null,
                'status' => $status,
                'production_date' => $productionDate,
                'deadline' => $deadline,
                'notes' => $faker->sentence(),
                'approved_at' => $approvedAt,
                'completed_at' => $completedAt,
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
            ]);

            // Add 1 Output Item (Barang Jadi)
            $outputProduct = $barangJadi->random();
            $qtyPlanned = $faker->numberBetween(100, 1000);
            $qtyUsed = ($status === 'completed') ? $qtyPlanned : (($status === 'in_progress') ? $faker->numberBetween(0, $qtyPlanned) : 0);

            SpkItem::create([
                'spk_id' => $spk->id,
                'product_id' => $outputProduct->id,
                'item_type' => 'output',
                'quantity_planned' => $qtyPlanned,
                'quantity_used' => $qtyUsed,
                'unit' => $outputProduct->unit,
                'notes' => 'Target produksi',
            ]);

            // Add 3-5 Bahan Baku Items
            $numBahan = $faker->numberBetween(3, 5);
            $bahanItems = $bahanBaku->random($numBahan);

            foreach ($bahanItems as $bahan) {
                SpkItem::create([
                    'spk_id' => $spk->id,
                    'product_id' => $bahan->id,
                    'item_type' => 'bahan_baku',
                    'quantity_planned' => $faker->numberBetween(50, 200),
                    'quantity_used' => ($status === 'completed') ? $faker->numberBetween(50, 200) : 0,
                    'unit' => $bahan->unit,
                ]);
            }

            // Add 1-2 Packaging Items
            if ($packaging->count() > 0) {
                $numPack = $faker->numberBetween(1, 2);
                $packItems = $packaging->random(min($numPack, $packaging->count()));

                foreach ($packItems as $pack) {
                    SpkItem::create([
                        'spk_id' => $spk->id,
                        'product_id' => $pack->id,
                        'item_type' => 'packaging',
                        'quantity_planned' => $faker->numberBetween(100, 1000),
                        'quantity_used' => ($status === 'completed') ? $faker->numberBetween(100, 1000) : 0,
                        'unit' => $pack->unit,
                    ]);
                }
            }
        }
    }
}
