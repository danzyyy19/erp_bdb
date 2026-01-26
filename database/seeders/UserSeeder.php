<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Owner
        User::create([
            'name' => 'Mulyadi',
            'email' => 'mulyadi@bdb.site',
            'password' => bcrypt('password'),
            'role' => 'owner',
            'is_active' => true,
        ]);

        // Finance
        User::create([
            'name' => 'Yuyun',
            'email' => 'yuyun@bdb.site',
            'password' => bcrypt('password'),
            'role' => 'finance',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Naelufar',
            'email' => 'naelufar@bdb.site',
            'password' => bcrypt('password'),
            'role' => 'finance',
            'is_active' => true,
        ]);

        // Operasional
        User::create([
            'name' => 'Fiqri Haiqal',
            'email' => 'fiqri.haiqal@bdb.site',
            'password' => bcrypt('password'),
            'role' => 'operasional',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Admin',
            'email' => 'admin@bdb.site',
            'password' => bcrypt('password'),
            'role' => 'operasional',
            'is_active' => true,
        ]);
    }
}
