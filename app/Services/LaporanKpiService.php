<?php

namespace App\Services;

use App\Models\SasaranStrategis;

class LaporanKpiService
{
    private array $daftarLevel = [
        ['kunci' => 'id_sasaran_strategis',      'kolomNama' => 'nama_sasaran_strategis',      'relasiAnak' => 'indikatorKpi'],
        ['kunci' => 'id_indikator_kpi',          'kolomNama' => 'nama_indikator_kpi',          'relasiAnak' => 'sasaranInisiatif'],
        ['kunci' => 'id_sasaran_inisiatif',      'kolomNama' => 'nama_sasaran_inisiatif',      'relasiAnak' => 'indikatorInisiatif'],
        ['kunci' => 'id_indikator_inisiatif',    'kolomNama' => 'nama_indikator_inisiatif',    'relasiAnak' => 'programs'],
        ['kunci' => 'id_program',                'kolomNama' => 'nama_program',                'relasiAnak' => 'indikatorProgram'],
        ['kunci' => 'id_indikator_program',      'kolomNama' => 'nama_indikator_program',      'relasiAnak' => 'kegiatan'],
        ['kunci' => 'id_kegiatan',               'kolomNama' => 'nama_kegiatan',               'relasiAnak' => 'indikatorKegiatan'],
        ['kunci' => 'id_indikator_kegiatan',     'kolomNama' => 'nama_indikator_kegiatan',     'relasiAnak' => 'subKegiatan'],
        ['kunci' => 'id_sub_kegiatan',           'kolomNama' => 'judul_sub_kegiatan',          'relasiAnak' => 'indikatorSubKegiatan'],
        ['kunci' => 'id_indikator_sub_kegiatan', 'kolomNama' => 'nama_indikator_sub_kegiatan', 'relasiAnak' => null],
    ];

    public function daftarJudulKolom(): array
    {
        return [
            'Sasaran Strategis',
            'Indikator KPI',
            'Sasaran Inisiatif',
            'Indikator Inisiatif',
            'Program',
            'Indikator Program',
            'Kegiatan',
            'Indikator Kegiatan',
            'Sub Kegiatan',
            'Indikator Sub Kegiatan',
        ];
    }

    public function susunBaris(int $idTahun, ?int $idUnit = null): array
    {
        $relasiSampaiIndikatorProgram = 'indikatorKpi.sasaranInisiatif.indikatorInisiatif.programs.indikatorProgram';

        $batasanUnit = function ($kueriIndikatorProgram) use ($idUnit) {
            if ($idUnit !== null) {
                $kueriIndikatorProgram->whereHas('picUnit', function ($kueriPicUnit) use ($idUnit) {
                    $kueriPicUnit->where('id_unit', $idUnit);
                });
            }
        };

        $daftarSasaranStrategis = SasaranStrategis::with([
            $relasiSampaiIndikatorProgram => $batasanUnit,
            $relasiSampaiIndikatorProgram . '.kegiatan.indikatorKegiatan.subKegiatan.indikatorSubKegiatan',
        ])->where('id_tahun', $idTahun)->get();

        $daftarJalur = [];
        foreach ($daftarSasaranStrategis as $sasaranStrategis) {
            $this->telusuri($sasaranStrategis, 0, [], $daftarJalur);
        }

        // Jika difilter per unit, buang jalur yang tidak sampai ke indikator program
        // (indeks level indikator program = 5, jadi jalur harus berisi minimal 6 node)
        if ($idUnit !== null) {
            $jumlahNodeMinimalSampaiIndikatorProgram = 6;
            $daftarJalur = array_values(array_filter(
                $daftarJalur,
                fn($jalur) => count($jalur) >= $jumlahNodeMinimalSampaiIndikatorProgram
            ));
        }

        return $this->hitungRowspan($daftarJalur);
    }

    // Setiap jalur = satu baris tabel (dari akar sampai daun terdalam yang ada)
    private function telusuri($node, int $indeksLevel, array $jalur, array &$daftarJalur): void
    {
        $jalur[] = $node;
        $relasiAnak = $this->daftarLevel[$indeksLevel]['relasiAnak'];
        $daftarAnak = $relasiAnak ? $node->{$relasiAnak} : collect();

        if ($daftarAnak->isEmpty()) {
            $daftarJalur[] = $jalur;
            return;
        }

        foreach ($daftarAnak as $anak) {
            $this->telusuri($anak, $indeksLevel + 1, $jalur, $daftarJalur);
        }
    }

    private function hitungRowspan(array $daftarJalur): array
    {
        $jumlahBaris = count($daftarJalur);
        $jumlahKolom = count($this->daftarLevel);

        // kunci gabungan id dari akar sampai kolom ini, supaya hanya induk yang sama yang di-merge
        $kunciSel = [];
        foreach ($daftarJalur as $indeksBaris => $jalur) {
            $kunciBerjalan = '';
            for ($indeksKolom = 0; $indeksKolom < $jumlahKolom; $indeksKolom++) {
                if (! isset($jalur[$indeksKolom])) {
                    $kunciSel[$indeksBaris][$indeksKolom] = null;
                    continue;
                }
                $kunciBerjalan .= '/' . $jalur[$indeksKolom]->{$this->daftarLevel[$indeksKolom]['kunci']};
                $kunciSel[$indeksBaris][$indeksKolom] = $kunciBerjalan;
            }
        }

        $daftarBaris = [];
        for ($indeksBaris = 0; $indeksBaris < $jumlahBaris; $indeksBaris++) {
            for ($indeksKolom = 0; $indeksKolom < $jumlahKolom; $indeksKolom++) {
                $kunci = $kunciSel[$indeksBaris][$indeksKolom];
                $node = $daftarJalur[$indeksBaris][$indeksKolom] ?? null;
                $teks = $node ? $node->{$this->daftarLevel[$indeksKolom]['kolomNama']} : '-';

                $sudahDigabungKeAtas = $kunci !== null
                    && $indeksBaris > 0
                    && $kunciSel[$indeksBaris - 1][$indeksKolom] === $kunci;

                if ($sudahDigabungKeAtas) {
                    $daftarBaris[$indeksBaris][$indeksKolom] = ['teks' => $teks, 'rowspan' => 0];
                    continue;
                }

                $rowspan = 1;
                if ($kunci !== null) {
                    while (
                        $indeksBaris + $rowspan < $jumlahBaris
                        && $kunciSel[$indeksBaris + $rowspan][$indeksKolom] === $kunci
                    ) {
                        $rowspan++;
                    }
                }
                $daftarBaris[$indeksBaris][$indeksKolom] = ['teks' => $teks, 'rowspan' => $rowspan];
            }
        }

        return $daftarBaris;
    }
}
