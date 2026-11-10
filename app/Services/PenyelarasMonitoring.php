<?php

namespace App\Services;

use App\Models\IndikatorSubKegiatan;
use App\Models\Monitoring;
use App\Models\Periode;

class PenyelarasMonitoring
{
    /**
     * Menyamakan baris monitoring sebuah indikator sub kegiatan dengan periode_pengukuran
     * indikator programnya: baris yang kurang dibuat, baris salah periode yang belum
     * terisi dihapus, baris salah periode yang sudah terisi ditahan (tidak dihapus).
     */
    public function selaraskan(IndikatorSubKegiatan $indikatorSubKegiatan, int $idPengguna): array
    {
        $jenisPeriode = $indikatorSubKegiatan->ambilIndikatorProgram()->periode_pengukuran;
        $idTahun = $indikatorSubKegiatan->ambilIdTahun();

        $daftarIdPeriodeSeharusnya = Periode::where('id_tahun', $idTahun)
            ->where('jenis_periode', $jenisPeriode)
            ->pluck('id_periode');

        $jumlahDihapus = 0;
        $daftarBarisDitahan = collect();

        $daftarBarisSalahPeriode = Monitoring::where('id_indikator_sub_kegiatan', $indikatorSubKegiatan->id_indikator_sub_kegiatan)
            ->whereNotIn('id_periode', $daftarIdPeriodeSeharusnya)
            ->get();

        foreach ($daftarBarisSalahPeriode as $barisMonitoring) {
            if ($barisMonitoring->sudahTerisi()) {
                $daftarBarisDitahan->push($barisMonitoring);
                continue;
            }
            $barisMonitoring->delete();
            $jumlahDihapus++;
        }

        $daftarIdPeriodeSudahAda = Monitoring::where('id_indikator_sub_kegiatan', $indikatorSubKegiatan->id_indikator_sub_kegiatan)
            ->pluck('id_periode');

        $daftarIdPeriodeKurang = $daftarIdPeriodeSeharusnya->diff($daftarIdPeriodeSudahAda);

        foreach ($daftarIdPeriodeKurang as $idPeriode) {
            Monitoring::create([
                'id_periode'                => $idPeriode,
                'id_indikator_sub_kegiatan' => $indikatorSubKegiatan->id_indikator_sub_kegiatan,
                'status'                    => 'notstarted',
                'last_updated_by'           => $idPengguna,
                'last_updated_at'           => now(),
            ]);
        }

        return [
            'dibuat'  => $daftarIdPeriodeKurang->count(),
            'dihapus' => $jumlahDihapus,
            'ditahan' => $daftarBarisDitahan,
        ];
    }
}
