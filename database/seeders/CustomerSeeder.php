<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Customer;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $customers = [
            [
                'name' => 'PT. Sinar Jaya Abadi',
                'email' => 'purchasing@sinarjaya.com',
                'phone' => '021-5551234',
                'address' => 'Jl. Raya Industri No. 45, Cikarang',
                'company' => 'Sinar Jaya Group',
            ],
            [
                'name' => 'CV. Maju Makmur Sentosa',
                'email' => 'admin@majumakmur.co.id',
                'phone' => '031-8885678',
                'address' => 'Kawasan Industri Rungkut, Surabaya',
                'company' => 'Maju Makmur Co.',
            ],
            [
                'name' => 'PT. Bangun Perkasa',
                'email' => 'procurement@bangunperkasa.com',
                'phone' => '022-7779012',
                'address' => 'Jl. Soekarno Hatta No. 88, Bandung',
                'company' => 'Bangun Perkasa Tbk',
            ],
            [
                'name' => 'Toko Cat Sejahtera',
                'email' => 'budi.santoso@gmail.com',
                'phone' => '081234567890',
                'address' => 'Jl. Gajah Mada No. 12, Semarang',
                'company' => 'Perorangan',
            ],
            [
                'name' => 'PT. Konstruksi Utama',
                'email' => 'supply@konstruksiutama.com',
                'phone' => '021-9993456',
                'address' => 'Business Park Kebon Jeruk, Jakarta Barat',
                'company' => 'Konstruksi Utama Group',
            ],
            [
                'name' => 'UD. Sumber Rejeki',
                'email' => 'sumber.rejeki@yahoo.com',
                'phone' => '081398765432',
                'address' => 'Pasar Besar Blok A No. 5, Malang',
                'company' => 'UD Sumber Rejeki',
            ],
            [
                'name' => 'PT. Indochem Pratama',
                'email' => 'info@indochem.co.id',
                'phone' => '021-4441112',
                'address' => 'Kawasan Industri Pulogadung, Jakarta Timur',
                'company' => 'Indochem Pratama',
            ],
            [
                'name' => 'CV. Warna Warni',
                'email' => 'order@warnawarni.com',
                'phone' => '0274-555888',
                'address' => 'Jl. Magelang Km. 5, Yogyakarta',
                'company' => 'Warna Warni Group',
            ],
            [
                'name' => 'PT. Nusantara Paint',
                'email' => 'rawmaterial@nusantarapaint.com',
                'phone' => '021-6667778',
                'address' => 'Jl. Daan Mogot Km. 18, Tangerang',
                'company' => 'Nusantara Paint',
            ],
            [
                'name' => 'Bengkel Mobil "Kilat"',
                'email' => 'anto.bengkel@gmail.com',
                'phone' => '085678901234',
                'address' => 'Jl. Pahlawan No. 100, Medan',
                'company' => 'Perorangan',
            ],
        ];

        foreach ($customers as $customer) {
            $customer['uuid'] = Str::uuid()->toString();
            Customer::create($customer);
        }
    }
}
