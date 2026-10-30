<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class NotifikasiTugasService
{
    public const JENIS_TUGAS_BARU     = 'tugas_baru';
    public const JENIS_TENGGAT_3_HARI = 'tenggat_3_hari';
    public const JENIS_TENGGAT_1_HARI = 'tenggat_1_hari';
    public const JENIS_TERLAMBAT      = 'terlambat';

    private const JUMLAH_HARI_TUGAS_BARU = 3;

    private const KOLOM_DIBACA_PER_JENIS = [
        self::JENIS_TUGAS_BARU     => 'dibaca_tugas_baru_pada',
        self::JENIS_TENGGAT_3_HARI => 'dibaca_tenggat_3_hari_pada',
        self::JENIS_TENGGAT_1_HARI => 'dibaca_tenggat_1_hari_pada',
        self::JENIS_TERLAMBAT      => 'dibaca_terlambat_pada',
    ];

    private const TAMPILAN_PER_JENIS = [
        self::JENIS_TUGAS_BARU     => ['ikon' => 'bx bx-task',         'warna' => 'bg-primary'],
        self::JENIS_TENGGAT_3_HARI => ['ikon' => 'bx bx-time-five',    'warna' => 'bg-info'],
        self::JENIS_TENGGAT_1_HARI => ['ikon' => 'bx bx-alarm',        'warna' => 'bg-warning'],
        self::JENIS_TERLAMBAT      => ['ikon' => 'bx bx-error-circle', 'warna' => 'bg-danger'],
    ];

    public static function daftarJenisNotifikasi(): array
    {
        return array_keys(self::KOLOM_DIBACA_PER_JENIS);
    }

    public function ambilSemuaNotifikasi(int $idPengguna): Collection
    {
        $hariIni             = Carbon::today();
        $batasTugasBaru      = now()->subDays(self::JUMLAH_HARI_TUGAS_BARU);
        $batasTenggatTerjauh = $hariIni->copy()->addDays(3)->toDateString();

        $daftarBarisTugas = DB::table('pic_task')
            ->join('task', 'task.id_task', '=', 'pic_task.id_task')
            ->leftJoin('users as pemberi', 'pemberi.id_user', '=', 'task.assigned_by')
            ->where('pic_task.id_user', $idPengguna)
            ->where('task.status', '!=', 'done')
            ->where(function ($kondisi) use ($idPengguna) {
                $kondisi->whereNull('task.assigned_by')
                    ->orWhere('task.assigned_by', '!=', $idPengguna);
            })
            ->where(function ($kondisi) use ($batasTugasBaru, $batasTenggatTerjauh) {
                $kondisi->where('pic_task.ditugaskan_pada', '>=', $batasTugasBaru)
                    ->orWhere('task.tenggat_waktu', '<=', $batasTenggatTerjauh);
            })
            ->select(
                'pic_task.id_task',
                'pic_task.ditugaskan_pada',
                'pic_task.dibaca_tugas_baru_pada',
                'pic_task.dibaca_tenggat_3_hari_pada',
                'pic_task.dibaca_tenggat_1_hari_pada',
                'pic_task.dibaca_terlambat_pada',
                'task.judul_tugas',
                'task.tenggat_waktu',
                'pemberi.nama as nama_pemberi'
            )
            ->get();

        $daftarNotifikasi = collect();

        foreach ($daftarBarisTugas as $baris) {
            $tanggalTenggat = Carbon::parse($baris->tenggat_waktu)->startOfDay();
            $sisaHari       = (int) $hariIni->diffInDays($tanggalTenggat, false);

            // 1. Tugas baru
            if ($baris->ditugaskan_pada !== null
                && Carbon::parse($baris->ditugaskan_pada)->greaterThanOrEqualTo($batasTugasBaru)) {
                $namaPemberi = $baris->nama_pemberi ?? 'pemberi tugas';

                $daftarNotifikasi->push($this->buatNotifikasi(
                    $baris,
                    self::JENIS_TUGAS_BARU,
                    "Tugas baru dari {$namaPemberi}: {$baris->judul_tugas}",
                    Carbon::parse($baris->ditugaskan_pada),
                    $baris->dibaca_tugas_baru_pada
                ));
            }

            // 2. Pengingat tenggat atau terlambat (hanya satu yang berlaku)
            if ($sisaHari >= 2 && $sisaHari <= 3) {
                $daftarNotifikasi->push($this->buatNotifikasi(
                    $baris,
                    self::JENIS_TENGGAT_3_HARI,
                    "Tenggat {$sisaHari} hari lagi: {$baris->judul_tugas}",
                    $tanggalTenggat->copy()->subDays(3),
                    $baris->dibaca_tenggat_3_hari_pada
                ));
            } elseif ($sisaHari >= 0 && $sisaHari <= 1) {
                $keteranganWaktu = $sisaHari === 0 ? 'hari ini' : 'besok';

                $daftarNotifikasi->push($this->buatNotifikasi(
                    $baris,
                    self::JENIS_TENGGAT_1_HARI,
                    "Tenggat {$keteranganWaktu}: {$baris->judul_tugas}",
                    $tanggalTenggat->copy()->subDay(),
                    $baris->dibaca_tenggat_1_hari_pada
                ));
            } elseif ($sisaHari < 0) {
                $jumlahHariTerlambat = abs($sisaHari);

                $daftarNotifikasi->push($this->buatNotifikasi(
                    $baris,
                    self::JENIS_TERLAMBAT,
                    "Sudah terlambat {$jumlahHariTerlambat} hari: {$baris->judul_tugas}",
                    $tanggalTenggat->copy()->addDay(),
                    $baris->dibaca_terlambat_pada
                ));
            }
        }

        return $daftarNotifikasi
            ->sortByDesc(fn (array $notifikasi) => $notifikasi['waktu_notifikasi']->timestamp)
            ->values();
    }

    public function penggunaAdalahPenerima(int $idPengguna, int $idTask): bool
    {
        return DB::table('pic_task')
            ->where('id_user', $idPengguna)
            ->where('id_task', $idTask)
            ->exists();
    }

    public function tandaiSudahDibaca(int $idPengguna, int $idTask, string $jenisNotifikasi): void
    {
        $namaKolomDibaca = self::KOLOM_DIBACA_PER_JENIS[$jenisNotifikasi] ?? null;

        if ($namaKolomDibaca === null) {
            return;
        }

        DB::table('pic_task')
            ->where('id_user', $idPengguna)
            ->where('id_task', $idTask)
            ->whereNull($namaKolomDibaca)
            ->update([$namaKolomDibaca => now()]);
    }

    public function tandaiSemuaSudahDibaca(int $idPengguna): void
    {
        $kelompokBelumDibaca = $this->ambilSemuaNotifikasi($idPengguna)
            ->where('sudah_dibaca', false)
            ->groupBy('jenis_notifikasi');

        foreach ($kelompokBelumDibaca as $jenisNotifikasi => $kelompokNotifikasi) {
            $namaKolomDibaca = self::KOLOM_DIBACA_PER_JENIS[$jenisNotifikasi];

            DB::table('pic_task')
                ->where('id_user', $idPengguna)
                ->whereIn('id_task', $kelompokNotifikasi->pluck('id_task')->all())
                ->whereNull($namaKolomDibaca)
                ->update([$namaKolomDibaca => now()]);
        }
    }

    private function buatNotifikasi(
        object $baris,
        string $jenisNotifikasi,
        string $pesanNotifikasi,
        Carbon $waktuNotifikasi,
        ?string $waktuDibaca
    ): array {
        return [
            'id_task'          => (int) $baris->id_task,
            'jenis_notifikasi' => $jenisNotifikasi,
            'pesan_notifikasi' => $pesanNotifikasi,
            'waktu_notifikasi' => $waktuNotifikasi,
            'sudah_dibaca'     => $waktuDibaca !== null,
            'ikon'             => self::TAMPILAN_PER_JENIS[$jenisNotifikasi]['ikon'],
            'warna'            => self::TAMPILAN_PER_JENIS[$jenisNotifikasi]['warna'],
        ];
    }
}