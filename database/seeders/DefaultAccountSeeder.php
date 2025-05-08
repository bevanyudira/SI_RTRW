<?php

namespace Database\Seeders;

use App\Models\Pejabat;
use App\Models\Rt;
use App\Models\Rw;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DefaultAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warga = Warga::factory()->create(["rt_id" => 1]);

        User::factory()->create([
            "warga_id" => $warga->id,
            "role" => "Super_Admin",
            "activated" => true,
            "email" => "superadmin@gmail.com"
        ]);

        $rt = Rt::all();

        foreach ($rt as $item) {
            $warga = Warga::factory()->create(["rt_id" => $item->id]);
            User::factory()->create([
                "warga_id" => $warga->id,
                "role" => "Admin_RT",
                "activated" => true,
                "email" => "adminrt" . $item->id . "@gmail.com"
            ]);
            $warga = Warga::factory()->create(["rt_id" => $item->id]);
            User::factory()->create([
                "warga_id" => $warga->id,
                "role" => "Ketua_RT",
                "activated" => true,
                "email" => "ketuart" . $item->id . "@gmail.com"
            ]);
        }
        $rw = Rw::all();

        foreach ($rw as $item) {
            $rt = $item->rts->first();
            $warga = Warga::factory()->create(["rt_id" => $item->id]);
            User::factory()->create([
                "warga_id" => $warga->id,
                "role" => "Ketua_RW",
                "activated" => true,
                "email" => "ketuarw" . $item->id . "@gmail.com"
            ]);
            $warga = Warga::factory()->create(["rt_id" => $item->id]);
            User::factory()->create([
                "warga_id" => $warga->id,
                "role" => "Admin_RW",
                "activated" => true,
                "email" => "adminrw" . $item->id . "@gmail.com"
            ]);
        }
    }
}
