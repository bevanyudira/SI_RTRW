<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "email" => fake()->safeEmail(),
            "phone" => fake()->phoneNumber(),
            "password" => Hash::make('password'),
            "role" => fake()->randomElement(['Ketua_RT', 'Ketua_RW', 'Admin_RT', 'Admin_RW', 'Super_Admin', 'Warga']),
            "activated" => fake()->boolean(),
            "pin" => fake()->numberBetween(1000, 9999)
        ];
    }
}
