<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Proker>
 */
class ProkerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "title" => fake()->sentence(4),
            "description" => fake()->paragraph(),
            "time" => fake()->time(),
            "date" => fake()->date(),
            "location" => fake()->address(),
            "image" => fake()->sentence(3),
            "status" => fake()->randomElement(['progress', 'done'])
        ];
    }
}
