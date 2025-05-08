<?php

namespace Database\Seeders;

use App\Models\Rt;
use App\Models\Rw;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RtSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rw = Rw::all();
        foreach ($rw as $item) {
            Rt::factory(5)->create(["rw_id" => $item->id]);
        }
    }
}
