<?php

namespace Database\Seeders;

use App\Models\IuranRw;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class IuranRWSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        $listBulan = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        $listJenisIuran = ['bulanan', 'tambahan'];

        IuranRw::create([
            'rw_id' => 1,
            'nama_iuran' => 'Iuran Bulanan',
            'bulan' => $listBulan[$faker->numberBetween(0, 11)],
            'total_iuran' => $faker->randomFloat(3, 0, 1000),
            'jenis_iuran' => $faker->randomElement($listJenisIuran),
        ]);
    }
}
