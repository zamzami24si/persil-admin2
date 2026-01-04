<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PetaPersilSeeder extends Seeder
{
    public function run(): void
    {
        $persilIds = DB::table('persil')->pluck('persil_id')->toArray();

        $data = [];

        for ($i = 1; $i <= 100; $i++) {
            $persilId = $persilIds[array_rand($persilIds)];
            $panjang = mt_rand(10, 200);
            $lebar = mt_rand(10, 150);

            // Generate sample GeoJSON polygon
            $geojson = json_encode([
                'type' => 'Feature',
                'properties' => [
                    'name' => 'Persil ' . $persilId,
                    'persil_id' => $persilId,
                    'area_m2' => $panjang * $lebar,
                    'created' => date('Y-m-d')
                ],
                'geometry' => [
                    'type' => 'Polygon',
                    'coordinates' => [[
                        [107.6 + (mt_rand(0, 1000) / 10000), -6.9 + (mt_rand(0, 1000) / 10000)],
                        [107.61 + (mt_rand(0, 1000) / 10000), -6.9 + (mt_rand(0, 1000) / 10000)],
                        [107.61 + (mt_rand(0, 1000) / 10000), -6.91 + (mt_rand(0, 1000) / 10000)],
                        [107.6 + (mt_rand(0, 1000) / 10000), -6.91 + (mt_rand(0, 1000) / 10000)],
                        [107.6 + (mt_rand(0, 1000) / 10000), -6.9 + (mt_rand(0, 1000) / 10000)]
                    ]]
                ]
            ]);

            $data[] = [
                'persil_id' => $persilId,
                'geojson' => $geojson,
                'panjang_m' => $panjang,
                'lebar_m' => $lebar,
                'created_at' => now()->subDays(mt_rand(1, 365)),
                'updated_at' => now(),
            ];
        }

        DB::table('peta_persil')->insert($data);
    }
}
