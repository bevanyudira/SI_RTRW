<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warga = Warga::all();
        foreach ($warga as $item) {
            User::factory()->create(["warga_id" => $item->id, "role" => "Warga"]);
        }
    }
}
