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
        // Hardcode his login so you can test the admin view easily
        User::create([
            'name' => 'Bapak Kepala Desa',
            'phone' => '081111111111', // Admin Phone
            'password' => bcrypt('1234'), // Admin PIN
            'role' => 'admin',
        ]);

        // 2. Spawn your personal test account
        $you = User::create([
            'name' => 'Kang Tester',
            'phone' => '082222222222', // Your testing phone
            'password' => bcrypt('1234'),
            'role' => 'farmer',
        ]);

        // Give yourself 2 pieces of land
        Land::factory(2)->create(['user_id' => $you->id]);

        // 3. Spawn 10 random farmers, give each of them 1 to 3 pieces of land
        User::factory(10)->create()->each(function ($farmer) {
            Land::factory(rand(1, 3))->create([
                'user_id' => $farmer->id
            ]);
        });
    }
}