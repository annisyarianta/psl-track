<?php

namespace Database\Seeders;

use App\Models\Tahun;
use App\Models\PeriodeTw;
use Illuminate\Database\Seeder;

class TahunSeeder extends Seeder
{
    public function run(): void
    {
        // Generate tahun 2025 - 2032
        for ($tahun = 2025; $tahun <= 2032; $tahun++) {

            $dataTahun = Tahun::create([
                'tahun' => $tahun,
            ]);

            // Generate Triwulan I - IV
            for ($i = 1; $i <= 4; $i++) {
                PeriodeTw::create([
                    'id_tahun' => $dataTahun->id_tahun,
                    'triwulan' => $i,
                ]);
            }
        }
    }
}