<?php

namespace Database\Seeders;

use App\Models\Iuran;
use App\Models\Rt;
use App\Models\Rw;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class IuranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rw = Rw::all();

        foreach ($rw as $item) {
            Iuran::factory(5)->create(["rw_id" => $item->id, "rt_id" => null]);
        }
        $rt = Rt::all();

        foreach ($rt as $item) {
            Iuran::factory(5)->create(["rt_id" => $item->id, "rw_id" => null]);
        }
    }
}
