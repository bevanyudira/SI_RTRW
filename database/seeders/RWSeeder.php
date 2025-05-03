<?php

namespace Database\Seeders;

use App\Models\Rw;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RWSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        Rw::create([
            'nama_rw' => $faker->unique()->name(),
            'nomer_rekening' => $faker->numerify('###############')
        ]);
    }
}
