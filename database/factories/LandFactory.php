<?php

namespace Database\Factories;

use App\Models\Land;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Land>
 */
class LandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // We will assign the user_id in the Seeder
            'nickname' => 'Sawah ' . fake()->word(), 
            'area_size' => fake()->randomFloat(2, 0.5, 3.0), // Between 0.5 and 3 Hectares
            // Fake GPS coordinates roughly around Java
            'lat' => fake()->latitude(-8.0, -6.0), 
            'lng' => fake()->longitude(106.0, 113.0),
        ];
    }
}
