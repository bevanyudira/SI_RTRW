<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RwSeeder::class,
            RtSeeder::class,
            ProkerSeeder::class,
            MutationSeeder::class,
            IuranSeeder::class,
            WargaSeeder::class,
            KritikSeeder::class,
            DefaultAccountSeeder::class,
        ]);
    }
}
