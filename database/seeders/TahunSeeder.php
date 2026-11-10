<?php

namespace Database\Seeders;

use App\Models\Periode;
use App\Models\Tahun;
use Illuminate\Database\Seeder;

class TahunSeeder extends Seeder
{
    public function run(): void
    {
        for ($nilaiTahun = 2025; $nilaiTahun <= 2030; $nilaiTahun++) {

            $dataTahun = Tahun::firstOrCreate([
                'tahun' => $nilaiTahun,
            ]);

            Periode::buatUntukTahun($dataTahun->id_tahun);
        }
    }
}