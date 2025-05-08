<?php

namespace Database\Seeders;

use App\Models\Kritik;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class KritikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::all();
        foreach ($user as $item) {
            Kritik::factory()->create([
                "rw_id" => null,
                "rt_id" => $item->warga->rt->id,
                "user_id" => $item->id
            ]);
            Kritik::factory()->create([
                "rt_id" => null,
                "rw_id" => $item->warga->rt->rw->id,
                "user_id" => $item->id
            ]);
        }
    }
}
