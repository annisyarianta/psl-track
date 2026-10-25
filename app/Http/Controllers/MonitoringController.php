<?php

namespace App\Http\Controllers;

use App\Models\FilePelaporan;
use App\Models\IndikatorSubKegiatan;
use App\Models\Monitoring;
use App\Models\PeriodeTw;
use App\Models\Tahun;
use App\Models\User;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MonitoringController extends Controller
{
    /*
     * Relasi dari IndikatorSubKegiatan untuk membatasi data sesuai peran.
     * picStaff sudah ada di modul pic staff; picUnit adalah dugaan, ubah di sini jika namanya berbeda.
     */
    private const RELASI_KE_PIC_STAFF = 'subKegiatan.indikatorKegiatan.picStaff';
    private const RELASI_KE_PIC_UNIT = 'subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram.picUnit';

    private const DAFTAR_STATUS = ['notstarted', 'onprogress', 'done'];

    public function index(Request $request)
    {
        $pengguna = $request->user();

        // Dropdown tahun hanya menampilkan tahun ini dan tahun yang sudah lewat.
        $daftarTahun = Tahun::where('tahun', '<=', now()->year)
            ->orderByDesc('tahun')
            ->get();

        $tahunDipilih = $daftarTahun->firstWhere('id_tahun', $request->input('id_tahun'))
            ?? $daftarTahun->firstWhere('tahun', now()->year)
            ?? $daftarTahun->first();

        abort_if(! $tahunDipilih, 404, 'Data tahun belum tersedia.');

        $daftarPeriodeTriwulan = PeriodeTw::where('id_tahun', $tahunDipilih->id_tahun)
            ->orderBy('triwulan')
            ->get();

        $periodeDipilih = $daftarPeriodeTriwulan->firstWhere('id_periode_tw', $request->input('id_periode_tw'))
            ?? ($tahunDipilih->tahun == now()->year
                ? $daftarPeriodeTriwulan->firstWhere('triwulan', now()->quarter)
                : $daftarPeriodeTriwulan->last())
            ?? $daftarPeriodeTriwulan->first();

        abort_if(! $periodeDipilih, 404, 'Periode triwulan belum tersedia untuk tahun ini.');

        $statusDipilih = in_array($request->input('status'), self::DAFTAR_STATUS, true)
            ? $request->input('status')
            : null;

        $queryMonitoring = Monitoring::where('id_periode_tw', $periodeDipilih->id_periode_tw)
            ->whereHas('indikatorSubKegiatan', function (Builder $queryIndikator) use ($pengguna) {
                $this->batasiSesuaiPeran($queryIndikator, $pengguna);
            });

        $jumlahPerStatus = (clone $queryMonitoring)
            ->selectRaw('status, COUNT(*) as jumlah')
            ->groupBy('status')
            ->pluck('jumlah', 'status');

        $ringkasanStatus = [
            'notstarted' => $jumlahPerStatus['notstarted'] ?? 0,
            'onprogress' => $jumlahPerStatus['onprogress'] ?? 0,
            'done' => $jumlahPerStatus['done'] ?? 0,
        ];

        if ($statusDipilih !== null) {
            $queryMonitoring->where('status', $statusDipilih);
        }

        $daftarMonitoring = $queryMonitoring
            ->with([
                'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.kegiatan',
                'lastUpdatedBy',
            ])
            ->orderBy('id_monitoring')
            ->paginate(10)
            ->withQueryString();

        $dapatMengisi = $pengguna->role !== 'manager';

        return view('monitoring.index', compact(
            'daftarTahun',
            'tahunDipilih',
            'daftarPeriodeTriwulan',
            'periodeDipilih',
            'statusDipilih',
            'ringkasanStatus',
            'daftarMonitoring',
            'dapatMengisi',
        ));
    }
    
    public function show(Request $request, Monitoring $monitoring)
    {
        $this->pastikanDapatMengakses($request->user(), $monitoring);

        $monitoring->load([
            'files',
            'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram.program',
        ]);

        $indikatorSubKegiatan = $monitoring->indikatorSubKegiatan;
        $subKegiatan = $indikatorSubKegiatan->subKegiatan;
        $indikatorProgram = $subKegiatan->indikatorKegiatan->kegiatan->indikatorProgram;

        return response()->json([
            'program' => $indikatorProgram->program->nama_program,
            'indikator_program' => $indikatorProgram->nama_indikator_program,
            'target_manager' => $indikatorProgram->target_manager,
            'aspek' => ucfirst((string) $indikatorProgram->aspek),
            'judul_sub_kegiatan' => $subKegiatan->judul_sub_kegiatan,
            'isi_sub_kegiatan' => $subKegiatan->isi_sub_kegiatan,
            'indikator_sub_kegiatan' => $indikatorSubKegiatan->nama_indikator_sub_kegiatan,
            'upaya' => $monitoring->upaya,
            'capaian' => $monitoring->capaian,
            'identifikasi' => $monitoring->identifikasi,
            'keterangan' => $monitoring->keterangan,
            'daftar_file' => $monitoring->files->map(fn(FilePelaporan $filePelaporan) => [
                'nama_file' => $filePelaporan->nama_file,
                'url_file' => asset('storage/' . $filePelaporan->path_file),
            ])->values(),
        ]);
    }

    public function edit(Request $request, Monitoring $monitoring)
    {
        $this->pastikanDapatMengisi($request->user(), $monitoring);

        return view('monitoring.form', $this->siapkanDataForm($monitoring));
    }

    public function update(Request $request, Monitoring $monitoring)
    {
        $pengguna = $request->user();

        $this->pastikanDapatMengisi($pengguna, $monitoring);

        $dataTervalidasi = $request->validate([
            'upaya' => ['required', 'string', 'max:255'],
            'capaian' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'identifikasi' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:notstarted,onprogress,done'],
            'file_pelaporan' => ['nullable', 'array'],
            'file_pelaporan.*' => ['file', 'max:10240', 'mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png'],
            'hapus_file' => ['nullable', 'array'],
            'hapus_file.*' => ['integer'],
        ]);

        DB::transaction(function () use ($request, $monitoring, $pengguna, $dataTervalidasi) {
            $monitoring->update([
                'upaya' => $dataTervalidasi['upaya'],
                'capaian' => $dataTervalidasi['capaian'],
                'keterangan' => $dataTervalidasi['keterangan'] ?? null,
                'identifikasi' => $dataTervalidasi['identifikasi'] ?? null,
                'status' => $dataTervalidasi['status'],
                'last_updated_by' => $pengguna->id_user,
                'last_updated_at' => now(),
            ]);

            $this->hapusFilePelaporanTerpilih($monitoring, $request->input('hapus_file', []));

            foreach ($request->file('file_pelaporan', []) as $berkasUnggahan) {
                FilePelaporan::create([
                    'id_monitoring' => $monitoring->id_monitoring,
                    'nama_file' => $berkasUnggahan->getClientOriginalName(),
                    'path_file' => $berkasUnggahan->store('file-pelaporan/' . $monitoring->id_monitoring, 'public'),
                    'uploaded_by' => $pengguna->id_user,
                    'uploaded_at' => now(),
                ]);
            }
        });

        $periodeTriwulan = $monitoring->periodeTw;

        return redirect()
            ->route('monitoring.index', [
                'id_tahun' => $periodeTriwulan->id_tahun,
                'id_periode_tw' => $periodeTriwulan->id_periode_tw,
            ])
            ->with('success', 'Monitoring berhasil disimpan.');
    }

    /**
     * Staff: indikator yang di-assign kepadanya. Asmen: indikator pada unitnya. Manager: semua.
     */
    private function batasiSesuaiPeran(Builder $queryIndikator, User $pengguna): Builder
    {
        if ($pengguna->role === 'staff') {
            $queryIndikator->whereHas(self::RELASI_KE_PIC_STAFF, function (Builder $queryPicStaff) use ($pengguna) {
                $queryPicStaff->where('id_user', $pengguna->id_user);
            });
        } elseif ($pengguna->role === 'asmen') {
            $queryIndikator->whereHas(self::RELASI_KE_PIC_UNIT, function (Builder $queryPicUnit) use ($pengguna) {
                $queryPicUnit->where('id_unit', $pengguna->id_unit);
            });
        }

        return $queryIndikator;
    }

    private function pastikanDapatMengakses(User $pengguna, Monitoring $monitoring): void
    {
        $dapatMengakses = $this->batasiSesuaiPeran(IndikatorSubKegiatan::query(), $pengguna)
            ->where('id_indikator_sub_kegiatan', $monitoring->id_indikator_sub_kegiatan)
            ->exists();

        abort_unless($dapatMengakses, 403);
    }

    private function pastikanDapatMengisi(User $pengguna, Monitoring $monitoring): void
    {
        abort_if($pengguna->role === 'manager', 403);

        $this->pastikanDapatMengakses($pengguna, $monitoring);
    }

    private function siapkanDataForm(Monitoring $monitoring): array
    {
        $monitoring->load([
            'files',
            'lastUpdatedBy',
            'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.kegiatan',
            'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.picStaff',
            'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram.picUnit',
        ]);

        $periodeTriwulan = $monitoring->periodeTw;

        $indikatorKegiatan = $monitoring->indikatorSubKegiatan->subKegiatan->indikatorKegiatan;
        $indikatorProgram = $indikatorKegiatan->kegiatan->indikatorProgram;

        $daftarNamaPicStaff = User::whereIn('id_user', $indikatorKegiatan->picStaff->pluck('id_user'))
            ->pluck('nama');

        $daftarNamaPicUnit = Unit::whereIn('id_unit', $indikatorProgram->picUnit->pluck('id_unit'))
            ->pluck('nama_unit');

        return [
            'monitoring' => $monitoring,
            'indikatorSubKegiatan' => $monitoring->indikatorSubKegiatan,
            'periodeTriwulan' => $periodeTriwulan,
            'tahunPeriode' => Tahun::findOrFail($periodeTriwulan->id_tahun),
            'daftarNamaPicStaff' => $daftarNamaPicStaff,
            'daftarNamaPicUnit' => $daftarNamaPicUnit,
        ];
    }

    private function hapusFilePelaporanTerpilih(Monitoring $monitoring, array $daftarIdFile): void
    {
        if ($daftarIdFile === []) {
            return;
        }

        FilePelaporan::where('id_monitoring', $monitoring->id_monitoring)
            ->whereIn('id_file', $daftarIdFile)
            ->get()
            ->each(function (FilePelaporan $filePelaporan) {
                Storage::disk('public')->delete($filePelaporan->path_file);
                $filePelaporan->delete();
            });
    }
}
