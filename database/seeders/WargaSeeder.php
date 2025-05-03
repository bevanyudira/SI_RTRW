<?php

namespace Database\Seeders;

use App\Models\Rt;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Hash;

class WargaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // for ($i=0; $i < 2; $i++) { 
        //     Warga::create([
        //         'nama' => fake()->name(),
        //         'email' => fake()->email(),
        //         'no_hp' => '08'.fake()->numerify('##########'),
        //         'username' => fake()->userName(),
        //         'password' => bcrypt(fake()->password()), // generate a random password
        //         'aktivasi' => fake()->randomElement(['Activated', 'Unactivated']),
        //     ]);
        // }
        $faker = Faker::create();

        $listRT = Rt::pluck('id')->toArray();

        for ($i = 0; $i < 3; $i++) {
            $warga = Warga::create([
                'nik' => $faker->numerify('################'),
                'rt_id' => $faker->randomElement($listRT),
                'nama' => $faker->unique()->name(),
                'alamat' => $faker->address()
            ]);
            User::create([
                "warga_id" => $warga->id,
                "email" => $faker->email(),
                "no_hp" => $faker->phoneNumber(),
                "password" => Hash::make('password'),
                "role" => 'Super_Admin',
            ]);
        }
    }
}
