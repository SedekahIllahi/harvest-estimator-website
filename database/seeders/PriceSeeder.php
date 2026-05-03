<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Price;

class PriceSeeder extends Seeder
{
    public function run()
    {
        $prices = [
            ['commodity' => 'Padi', 'slug' => 'padi', 'price_per_kg' => 5500, 'conversion_factor' => 4.2],
            ['commodity' => 'Jagung', 'slug' => 'jagung', 'price_per_kg' => 4200, 'conversion_factor' => 3.8],
            ['commodity' => 'Kedelai', 'slug' => 'kedelai', 'price_per_kg' => 8800, 'conversion_factor' => 2.5],
            ['commodity' => 'Singkong', 'slug' => 'singkong', 'price_per_kg' => 1800, 'conversion_factor' => 6.1],
            ['commodity' => 'Tebu', 'slug' => 'tebu', 'price_per_kg' => 2100, 'conversion_factor' => 7.3],
            ['commodity' => 'Kelapa Sawit', 'slug' => 'kelapa-sawit', 'price_per_kg' => 3600, 'conversion_factor' => 5.0],
        ];

        foreach ($prices as $price) {
            Price::create($price);
        }
    }
}