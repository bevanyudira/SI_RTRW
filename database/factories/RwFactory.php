<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rw>
 */
class RwFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => "RW " . fake()->numberBetween(1, 9),
            "bank" => fake()->numberBetween(10000000, 99999999),
            "balance" => fake()->numberBetween(1, 100) * 10000
        ];
    }
}
