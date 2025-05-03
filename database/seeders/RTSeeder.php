<?php

namespace Database\Seeders;

use App\Models\Rt;
use App\Models\Rw;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class RTSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $listRW = Rw::pluck('id')->toArray();

        Rt::create([
            'rw_id' => $faker->randomElement($listRW),
            'nama_rt' => $faker->unique()->name(),
            'nomor_rekening' => $faker->numerify('################')
        ]);
    }
}
