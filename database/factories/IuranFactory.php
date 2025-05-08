<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Iuran>
 */
class IuranFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->sentence(2),
            "value" => fake()->numberBetween(1, 100) * 1000,
            "month" => fake()->randomElement(['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December']),
            "variance" => fake()->randomElement(['monthly', 'additional'])
        ];
    }
}
