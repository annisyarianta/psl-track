<?php

namespace App\Http\Controllers;

use App\Models\FileTask;
use App\Models\Program;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    private const STATUS_REVISI = 'revisi';

    private const WARNA_STATUS = [
        'todo'       => '#74788d',
        'inprogress' => '#50a5f1',
        'done'       => '#34c38f',
        'revisi'     => '#f06548',
    ];

    public function index()
    {
        $idUserLogin = (int) Auth::user()->id_user;

        $daftarTask = Task::with(['program', 'pemberiTugas', 'penerimaTugas', 'files'])
            ->when(! $this->apakahManager(), fn($query) => $query->milikUser($idUserLogin))
            ->orderBy('tenggat_waktu')
            ->get();

        $daftarDetailTask = [];
        $daftarEventKalender = [];

        foreach ($daftarTask as $task) {
            $detailTask = $this->susunDetailTask($task, $idUserLogin);

            $daftarDetailTask[$task->id_task] = $detailTask;
            $daftarEventKalender[] = $this->susunEventKalender($task, $detailTask);
        }

        return view('task.index', [
            'daftarTask'          => $daftarTask,
            'daftarDetailTask'    => $daftarDetailTask,
            'daftarEventKalender' => $daftarEventKalender,
            'daftarStatus'        => Task::DAFTAR_STATUS,
            'idUserLogin'         => $idUserLogin,
            'bolehMemberiTugas'   => $this->apakahBolehMemberiTugas(),
        ]);
    }

    public function create()
    {
        $this->pastikanBolehMemberiTugas();

        return view('task.form', [
            'task'                => null,
            'daftarProgram'       => Program::orderBy('nama_program')->get(),
            'daftarCalonPenerima' => $this->ambilCalonPenerima(),
            // Task baru tidak bisa langsung berstatus revisi
            'daftarStatus'        => $this->ambilDaftarStatusYangBolehDipilih(true, true),
            'idUserLogin'         => (int) Auth::user()->id_user,
            'apakahPemberiTugas'  => true,
        ]);
    }

    public function store(Request $request)
    {
        $this->pastikanBolehMemberiTugas();

        $dataTervalidasi = $this->validasiForm($request, true, true);

        DB::transaction(function () use ($request, $dataTervalidasi) {
            $task = Task::create([
                'id_program'      => $dataTervalidasi['id_program'] ?? null,
                'judul_tugas'     => $dataTervalidasi['judul_tugas'],
                'deskripsi_tugas' => $dataTervalidasi['deskripsi_tugas'],
                'tenggat_waktu'   => $dataTervalidasi['tenggat_waktu'],
                'status'          => $dataTervalidasi['status'],
                'assigned_by'     => Auth::user()->id_user,
                'assigned_at'     => now(),
            ]);

            $idPenerimaTugas = array_map('intval', $dataTervalidasi['penerima_tugas']);

            $task->penerimaTugas()->attach($idPenerimaTugas, [
                'ditugaskan_pada' => now(),
            ]);
            $this->simpanFileTask($request, $task);
        });

        return redirect()->route('task.index')->with('success', 'Task berhasil ditambahkan.');
    }

    public function edit(Task $task)
    {
        $this->pastikanPemberiAtauPenerimaTugas($task);

        $idUserLogin        = (int) Auth::user()->id_user;
        $apakahPemberiTugas = $task->apakahPemberiTugas($idUserLogin);

        $task->load(['penerimaTugas', 'files']);

        return view('task.form', [
            'task'                => $task,
            'daftarProgram'       => Program::orderBy('nama_program')->get(),
            'daftarCalonPenerima' => $apakahPemberiTugas
                ? $this->ambilCalonPenerima()
                : $task->penerimaTugas->sortBy('nama')->values(),
            // Opsi Revisi hanya muncul untuk pemberi tugas
            'daftarStatus'        => $this->ambilDaftarStatusYangBolehDipilih($apakahPemberiTugas),
            'idUserLogin'         => $idUserLogin,
            'apakahPemberiTugas'  => $apakahPemberiTugas,
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $this->pastikanPemberiAtauPenerimaTugas($task);

        $idUserLogin        = (int) Auth::user()->id_user;
        $apakahPemberiTugas = $task->apakahPemberiTugas($idUserLogin);
        $tenggatLama        = Carbon::parse($task->tenggat_waktu)->toDateString();
        $dataTervalidasi    = $this->validasiForm($request, $apakahPemberiTugas);
        $statusLama           = $task->status;
        $keteranganRevisiLama = $task->keterangan_revisi;

        DB::transaction(function () use (
            $request,
            $task,
            $dataTervalidasi,
            $apakahPemberiTugas,
            $idUserLogin,
            $tenggatLama,
            $statusLama,
            $keteranganRevisiLama,
        ) {
            if ($apakahPemberiTugas) {
                $task->update(array_merge([
                    'id_program'        => $dataTervalidasi['id_program'] ?? null,
                    'judul_tugas'       => $dataTervalidasi['judul_tugas'],
                    'deskripsi_tugas'   => $dataTervalidasi['deskripsi_tugas'],
                    'tenggat_waktu'     => $dataTervalidasi['tenggat_waktu'],
                    'status'            => $dataTervalidasi['status'],
                    'keterangan_revisi' => $this->tentukanKeteranganRevisi($dataTervalidasi, $task),
                ], Arr::only($dataTervalidasi, ['hasil_tugas'])));

                $this->perbaruiPenerimaTugas($task, $dataTervalidasi['penerima_tugas']);

                if (Carbon::parse($task->tenggat_waktu)->toDateString() !== $tenggatLama) {
                    $this->resetStatusBacaPengingatTenggat($task);
                }
            } else {
                // Penerima tidak bisa memilih revisi, jadi keterangan revisi tidak disentuh
                $task->update(Arr::only($dataTervalidasi, ['status', 'hasil_tugas']));
            }
            $this->catatKejadianNotifikasiStatus($task, $statusLama, $keteranganRevisiLama, $idUserLogin);
            $this->hapusFileTerpilih($request, $task, $apakahPemberiTugas, $idUserLogin);
            $this->simpanFileTask($request, $task);
        });

        return redirect()->route('task.index')->with('success', 'Task berhasil diperbarui.');
    }

    public function destroy(Task $task)
    {
        $this->pastikanPemberiTugas($task);

        foreach ($task->files as $fileTask) {
            Storage::disk('public')->delete($fileTask->path_file);
        }

        $task->delete();

        return redirect()->route('task.index')->with('success', 'Task berhasil dihapus.');
    }

    public function updateStatus(Request $request, Task $task)
    {
        $this->pastikanPemberiAtauPenerimaTugas($task);
        $apakahPemberiTugas = $task->apakahPemberiTugas((int) Auth::user()->id_user);
        $dataTervalidasi = $request->validate([
            'status'            => [
                'required',
                Rule::in(array_keys($this->ambilDaftarStatusYangBolehDipilih($apakahPemberiTugas))),
            ],
            'keterangan_revisi' => ['nullable', 'required_if:status,' . self::STATUS_REVISI, 'string', 'max:1000'],
        ], $this->susunPesanValidasiStatus());

        $dataPembaruan = ['status' => $dataTervalidasi['status']];
        $apakahStatusRevisi = $dataTervalidasi['status'] === self::STATUS_REVISI;

        if ($apakahStatusRevisi) {
            $dataPembaruan['keterangan_revisi'] = $dataTervalidasi['keterangan_revisi'];
        }

        $statusLama           = $task->status;
        $keteranganRevisiLama = $task->keterangan_revisi;

        $task->update($dataPembaruan);

        $this->catatKejadianNotifikasiStatus(
            $task,
            $statusLama,
            $keteranganRevisiLama,
            (int) Auth::user()->id_user
        );

        return back()->with(
            'success',
            $apakahStatusRevisi ? 'Task dikembalikan untuk direvisi.' : 'Status task diperbarui.'
        );
    }

    // ---------- Helper ----------

    private function validasiForm(
        Request $request,
        bool $apakahPemberiTugas = true,
        bool $apakahTaskBaru = false
    ): array {
        $daftarNilaiStatusDiizinkan = array_keys(
            $this->ambilDaftarStatusYangBolehDipilih($apakahPemberiTugas, $apakahTaskBaru)
        );

        $aturanUntukSemuaPeran = [
            'status'            => ['required', Rule::in($daftarNilaiStatusDiizinkan)],
            'keterangan_revisi' => ['nullable', 'required_if:status,' . self::STATUS_REVISI, 'string', 'max:1000'],
            'hasil_tugas'       => ['nullable', 'string'],
            'file_task'         => ['nullable', 'array'],
            'file_task.*'       => ['file', 'max:10240'], // 10 MB per file
            'hapus_file'        => ['nullable', 'array'],
            'hapus_file.*'      => ['integer'],
        ];

        if (! $apakahPemberiTugas) {
            return $request->validate($aturanUntukSemuaPeran, $this->susunPesanValidasiStatus());
        }

        $daftarIdCalonPenerima = $this->ambilCalonPenerima()->pluck('id_user')->all();

        return $request->validate(array_merge($aturanUntukSemuaPeran, [
            'id_program'       => ['nullable', 'exists:program,id_program'],
            'judul_tugas'      => ['required', 'string', 'max:255'],
            'deskripsi_tugas'  => ['required', 'string'],
            'tenggat_waktu'    => ['required', 'date'],
            'penerima_tugas'   => ['required', 'array', 'min:1'],
            'penerima_tugas.*' => ['integer', Rule::in($daftarIdCalonPenerima)],
        ]), array_merge($this->susunPesanValidasiStatus(), [
            'penerima_tugas.required' => 'Pilih minimal satu penerima tugas.',
            'penerima_tugas.*.in'     => 'Penerima tugas tidak sesuai dengan hak akses Anda.',
        ]));
    }

    private function susunPesanValidasiStatus(): array
    {
        return [
            'status.in'                      => 'Status yang dipilih tidak diperbolehkan untuk Anda.',
            'keterangan_revisi.required_if'  => 'Keterangan revisi wajib diisi saat status Revisi.',
        ];
    }


    private function ambilDaftarStatusYangBolehDipilih(
        bool $apakahPemberiTugas,
        bool $apakahTaskBaru = false
    ): array {
        if ($apakahPemberiTugas && ! $apakahTaskBaru) {
            return Task::DAFTAR_STATUS;
        }

        return Arr::except(Task::DAFTAR_STATUS, [self::STATUS_REVISI]);
    }

    private function tentukanKeteranganRevisi(array $dataTervalidasi, Task $task): ?string
    {
        return $dataTervalidasi['status'] === self::STATUS_REVISI
            ? $dataTervalidasi['keterangan_revisi']
            : $task->keterangan_revisi;
    }

    private function catatKejadianNotifikasiStatus(
        Task $task,
        string $statusLama,
        ?string $keteranganRevisiLama,
        int $idUserLogin
    ): void {
        $apakahPemberiTugas = $task->apakahPemberiTugas($idUserLogin);
        if ($task->status === 'done' && $statusLama !== 'done' && ! $apakahPemberiTugas) {
            $task->update([
                'diselesaikan_oleh'   => $idUserLogin,
                'diselesaikan_pada'   => now(),
                'dibaca_selesai_pada' => null,
            ]);
        }

        if ($task->status !== 'done' && $statusLama === 'done') {
            $task->update([
                'diselesaikan_oleh'   => null,
                'diselesaikan_pada'   => null,
                'dibaca_selesai_pada' => null,
            ]);
        }

        $apakahRevisiBaru = $task->status === self::STATUS_REVISI
            && ($statusLama !== self::STATUS_REVISI || $task->keterangan_revisi !== $keteranganRevisiLama);

        if ($apakahRevisiBaru) {
            $task->update(['direvisi_pada' => now()]);

            DB::table('pic_task')
                ->where('id_task', $task->id_task)
                ->update(['dibaca_revisi_pada' => null]);
        }
    }

    private function perbaruiPenerimaTugas(Task $task, array $daftarIdPenerimaBaru): void
    {
        $idPenerimaBaru = array_map('intval', $daftarIdPenerimaBaru);

        $idPenerimaLama = DB::table('pic_task')
            ->where('id_task', $task->id_task)
            ->pluck('id_user')
            ->map(fn($idUser) => (int) $idUser)
            ->all();

        $idPenerimaDicopot  = array_diff($idPenerimaLama, $idPenerimaBaru);
        $idPenerimaDitambah = array_diff($idPenerimaBaru, $idPenerimaLama);

        if (! empty($idPenerimaDicopot)) {
            $task->penerimaTugas()->detach($idPenerimaDicopot);
        }

        if (! empty($idPenerimaDitambah)) {
            $task->penerimaTugas()->attach($idPenerimaDitambah, [
                'ditugaskan_pada' => now(),
            ]);
        }
    }

    private function resetStatusBacaPengingatTenggat(Task $task): void
    {
        DB::table('pic_task')
            ->where('id_task', $task->id_task)
            ->update([
                'dibaca_tenggat_3_hari_pada' => null,
                'dibaca_tenggat_1_hari_pada' => null,
                'dibaca_terlambat_pada'      => null,
            ]);
    }

    private function simpanFileTask(Request $request, Task $task): void
    {
        foreach ($request->file('file_task', []) as $berkas) {
            $pathTersimpan = $berkas->store('task', 'public');

            FileTask::create([
                'id_task'     => $task->id_task,
                'nama_file'   => $berkas->getClientOriginalName(),
                'path_file'   => $pathTersimpan,
                'uploaded_by' => Auth::user()->id_user,
                'uploaded_at' => now(),
            ]);
        }
    }

    private function hapusFileTerpilih(
        Request $request,
        Task $task,
        bool $apakahPemberiTugas,
        int $idUserLogin
    ): void {
        $daftarIdFileDihapus = $request->input('hapus_file', []);

        if (empty($daftarIdFileDihapus)) {
            return;
        }

        $queryFile = $task->files()->whereIn('id_file_task', $daftarIdFileDihapus);

        if (! $apakahPemberiTugas) {
            $queryFile->where('uploaded_by', $idUserLogin);
        }

        $queryFile->get()->each(function (FileTask $fileTask) {
            Storage::disk('public')->delete($fileTask->path_file);
            $fileTask->delete();
        });
    }

    private function ambilCalonPenerima()
    {
        $userLogin = Auth::user();

        return match ($userLogin->role) {
            'manager' => User::whereIn('role', ['asmen', 'staff'])
                ->orderBy('role')
                ->orderBy('nama')
                ->get(),

            'asmen' => User::where('role', 'staff')
                ->where('id_unit', $userLogin->id_unit)
                ->orderBy('nama')
                ->get(),

            default => collect(),
        };
    }

    private function apakahBolehMemberiTugas(): bool
    {
        return in_array(Auth::user()->role, ['manager', 'asmen'], true);
    }

    private function pastikanBolehMemberiTugas(): void
    {
        abort_unless($this->apakahBolehMemberiTugas(), 403);
    }

    private function pastikanPemberiTugas(Task $task): void
    {
        abort_unless($task->apakahPemberiTugas((int) Auth::user()->id_user), 403);
    }

    private function pastikanPemberiAtauPenerimaTugas(Task $task): void
    {
        $idUserLogin = (int) Auth::user()->id_user;

        abort_unless(
            $task->apakahPemberiTugas($idUserLogin) || $task->apakahPenerimaTugas($idUserLogin),
            403
        );
    }

    private function susunDetailTask(Task $task, int $idUserLogin): array
    {
        $apakahPemberiTugas = $task->apakahPemberiTugas($idUserLogin);
        $apakahPenerimaTugas = $task->penerimaTugas->contains('id_user', $idUserLogin);

        return [
            'namaProgram'      => $task->program->nama_program ?? '-',
            'judulTugas'       => $task->judul_tugas,
            'deskripsiTugas'   => $task->deskripsi_tugas,
            'hasilTugas'       => $task->hasil_tugas ?: '-',
            'tenggatWaktu'     => $task->tenggat_waktu->format('d M Y'),
            'pemberiTugas'     => $task->pemberiTugas->nama ?? '-',
            'daftarPenerima'   => $task->penerimaTugas->pluck('nama')->all(),
            'labelStatus'      => Task::DAFTAR_STATUS[$task->status],
            'keteranganRevisi' => $task->keterangan_revisi,
            'daftarFile'       => $task->files->map(fn(FileTask $fileTask) => [
                'namaFile' => $fileTask->nama_file,
                'urlFile'  => asset('storage/' . $fileTask->path_file),
            ])->all(),
            'urlEdit'          => ($apakahPemberiTugas || $apakahPenerimaTugas)
                ? route('task.edit', $task->id_task)
                : null,
        ];
    }

    private function susunEventKalender(Task $task, array $detailTask): array
    {
        $tanggalMulai = $task->assigned_at->copy()->startOfDay()->min($task->tenggat_waktu);
        $tanggalSelesai = $task->tenggat_waktu->copy()->addDay();

        return [
            'id'              => $task->id_task,
            'title'           => Str::limit($task->judul_tugas, 40),
            'start'           => $tanggalMulai->toDateString(),
            'end'             => $tanggalSelesai->toDateString(),
            'allDay'          => true,
            'backgroundColor' => self::WARNA_STATUS[$task->status],
            'borderColor'     => self::WARNA_STATUS[$task->status],
            'extendedProps'   => $detailTask,
        ];
    }

    private function apakahManager(): bool
    {
        return Auth::user()->role === 'manager';
    }
}
