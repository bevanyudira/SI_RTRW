<?php

namespace Database\Seeders;

use App\Models\Proker;
use App\Models\Rt;
use App\Models\Rw;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProkerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rw = Rw::all();

        foreach ($rw as $item) {
            Proker::factory(5)->create(["rw_id" => $item->id, "rt_id" => null]);
        }
        $rt = Rt::all();

        foreach ($rt as $item) {
            Proker::factory(5)->create(["rt_id" => $item->id, "rw_id" => null]);
        }
    }
}
