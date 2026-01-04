<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            WargaSeeder::class,
            JenisPenggunaanSeeder::class,
            PersilSeeder::class,
            DokumenPersilSeeder::class,
            PetaPersilSeeder::class,
            SengketaPersilSeeder::class,
            MediaSeeder::class,
        ]);
    }
}
