<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Land;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Spawn the Boss (Bapak Dukuh)
        User::create([
            'name' => 'Bapak Kepala Desa',
            'phone' => '081111111111',
            'password' => bcrypt('1234'),
            'role' => 'admin',
        ]);

        // 2. Spawn your personal test account
        $you = User::create([
            'name' => 'Kang Tester',
            'phone' => '082222222222',
            'password' => bcrypt('1234'),
            'role' => 'farmer',
        ]);

        Land::factory(2)->create(['user_id' => $you->id]);

        // 3. Spawn 10 random farmers with lands
        User::factory(10)->create()->each(function ($farmer) {
            Land::factory(rand(1, 3))->create([
                'user_id' => $farmer->id
            ]);
        });

        // 4. Call PriceSeeder to insert commodity prices and conversion factors
        $this->call(PriceSeeder::class);
    }
}