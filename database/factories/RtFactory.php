<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rt>
 */
class RtFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => "RT " . fake()->numberBetween(1, 9),
            "bank" => fake()->numberBetween(10000000, 99999999),
            "balance" => fake()->numberBetween(1, 100) * 10000,
        ];
    }
}
