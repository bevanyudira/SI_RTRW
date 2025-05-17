<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class MutationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "variance" => fake()->randomElement(['inflow', 'outflow']),
            "value" => fake()->numberBetween(1, 1000) * 100,
            "before" => fake()->numberBetween(1, 1000) * 100,
            "after" => fake()->numberBetween(1, 1000) * 100,
            "notes" => fake()->sentence(4),
            "image" => fake()->word(),
            "date" => fake()->date()
        ];
    }
}
