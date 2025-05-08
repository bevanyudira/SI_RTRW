<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Warga>
 */
class WargaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "nik" => fake()->numberBetween(10000000000000, 99999999999999),
            "name" => fake()->name(),
            "address" => fake()->address(),
            "birth" => fake()->date()
        ];
    }
}
