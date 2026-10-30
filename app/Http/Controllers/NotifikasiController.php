<?php

namespace App\Http\Controllers;

use App\Services\NotifikasiTugasService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NotifikasiController extends Controller
{
    private const JUMLAH_NOTIFIKASI_DITAMPILKAN = 20;

    public function __construct(
        private NotifikasiTugasService $notifikasiTugasService
    ) {}

    public function daftar(Request $request): JsonResponse
    {
        $idPengguna        = (int) $request->user()->id_user;
        $semuaNotifikasi   = $this->notifikasiTugasService->ambilSemuaNotifikasi($idPengguna);
        $jumlahBelumDibaca = $semuaNotifikasi->where('sudah_dibaca', false)->count();

        $daftarNotifikasi = $semuaNotifikasi
            ->take(self::JUMLAH_NOTIFIKASI_DITAMPILKAN)
            ->map(fn(array $notifikasi) => [
                'pesan_notifikasi' => $notifikasi['pesan_notifikasi'],
                'sudah_dibaca'     => $notifikasi['sudah_dibaca'],
                'ikon'             => $notifikasi['ikon'],
                'warna'            => $notifikasi['warna'],
                'waktu_relatif'    => $notifikasi['waktu_notifikasi']->diffForHumans(),
                'url_buka'         => route('notifikasi.buka', [
                    'idTask'          => $notifikasi['id_task'],
                    'jenisNotifikasi' => $notifikasi['jenis_notifikasi'],
                ]),
            ])
            ->values();

        return response()->json([
            'jumlah_belum_dibaca' => $jumlahBelumDibaca,
            'daftar_notifikasi'   => $daftarNotifikasi,
        ]);
    }

    public function buka(Request $request, int $idTask, string $jenisNotifikasi): RedirectResponse
    {
        $idPengguna = (int) $request->user()->id_user;

        abort_unless(
            in_array($jenisNotifikasi, NotifikasiTugasService::daftarJenisNotifikasi(), true),
            404
        );
        abort_unless(
            $this->notifikasiTugasService->penggunaAdalahPenerima($idPengguna, $idTask),
            404
        );

        $this->notifikasiTugasService->tandaiSudahDibaca($idPengguna, $idTask, $jenisNotifikasi);

        return redirect()->route('task.index', ['buka_detail' => $idTask]);
    }

    public function tandaiSemuaDibaca(Request $request): JsonResponse
    {
        $this->notifikasiTugasService->tandaiSemuaSudahDibaca((int) $request->user()->id_user);

        return response()->json(['berhasil' => true]);
    }
}
