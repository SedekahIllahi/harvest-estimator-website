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
            'name' => 'Admin Utama',
            'phone' => '081111111111',
            'password' => bcrypt('123456'),
            'role' => 'admin',
        ]);

        // 2. Spawn your personal test account
        $you = User::create([
            'name' => 'Kang Tester',
            'phone' => '082222222222',
            'password' => bcrypt('123456'),
            'role' => 'farmer',
        ]);

        Land::factory(2)->create(['user_id' => $you->id]);

        // 3. Spawn 10 random farmers with lands
        User::factory(10)->create()->each(function ($farmer) {
            Land::factory(rand(1, 3))->create([
                'user_id' => $farmer->id
            ]);
        });
    }
}