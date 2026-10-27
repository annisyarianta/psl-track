<?php

namespace App\Http\Controllers;

use App\Models\FileTask;
use App\Models\Program;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    private const WARNA_STATUS = [
        'todo'       => '#74788d',
        'inprogress' => '#50a5f1',
        'done'       => '#34c38f',
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
            'task'               => null,
            'daftarProgram'      => Program::orderBy('nama_program')->get(),
            'daftarCalonPenerima' => $this->ambilCalonPenerima(),
            'daftarStatus'       => Task::DAFTAR_STATUS,
        ]);
    }

    public function store(Request $request)
    {
        $this->pastikanBolehMemberiTugas();

        $dataTervalidasi = $this->validasiForm($request);

        DB::transaction(function () use ($request, $dataTervalidasi) {
            $task = Task::create([
                'id_program'      => $dataTervalidasi['id_program'] ?? null,
                'judul_tugas' => $dataTervalidasi['judul_tugas'],
                'deskripsi_tugas' => $dataTervalidasi['deskripsi_tugas'],
                'tenggat_waktu'   => $dataTervalidasi['tenggat_waktu'],
                'status'          => $dataTervalidasi['status'],
                'assigned_by'     => Auth::user()->id_user,
                'assigned_at'     => now(),
            ]);

            $task->penerimaTugas()->sync($dataTervalidasi['penerima_tugas']);
            $this->simpanFileTask($request, $task);
        });

        return redirect()->route('task.index')->with('success', 'Task berhasil ditambahkan.');
    }

    public function edit(Task $task)
    {
        $this->pastikanPemberiTugas($task);

        $task->load(['penerimaTugas', 'files']);

        return view('task.form', [
            'task'               => $task,
            'daftarProgram'      => Program::orderBy('nama_program')->get(),
            'daftarCalonPenerima' => $this->ambilCalonPenerima(),
            'daftarStatus'       => Task::DAFTAR_STATUS,
        ]);
    }

    public function update(Request $request, Task $task)
    {
        $this->pastikanPemberiTugas($task);

        $dataTervalidasi = $this->validasiForm($request);

        DB::transaction(function () use ($request, $task, $dataTervalidasi) {
            $task->update([
                'id_program'      => $dataTervalidasi['id_program'] ?? null,
                'judul_tugas' => $dataTervalidasi['judul_tugas'],
                'deskripsi_tugas' => $dataTervalidasi['deskripsi_tugas'],
                'tenggat_waktu'   => $dataTervalidasi['tenggat_waktu'],
                'status'          => $dataTervalidasi['status'],
            ]);

            $task->penerimaTugas()->sync($dataTervalidasi['penerima_tugas']);
            $this->hapusFileTerpilih($request, $task);
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
        $idUserLogin = (int) Auth::user()->id_user;

        abort_unless(
            $task->apakahPemberiTugas($idUserLogin) || $task->apakahPenerimaTugas($idUserLogin),
            403
        );

        $dataTervalidasi = $request->validate([
            'status' => ['required', Rule::in(array_keys(Task::DAFTAR_STATUS))],
        ]);

        $task->update(['status' => $dataTervalidasi['status']]);

        return back()->with('success', 'Status task diperbarui.');
    }


    // ---------- Helper ----------

    private function validasiForm(Request $request): array
    {
        $daftarIdCalonPenerima = $this->ambilCalonPenerima()->pluck('id_user')->all();

        return $request->validate([
            'id_program'        => ['nullable', 'exists:program,id_program'],
            'judul_tugas' => ['required', 'string', 'max:255'],
            'deskripsi_tugas'   => ['required', 'string'],
            'tenggat_waktu'     => ['required', 'date'],
            'status'            => ['required', Rule::in(array_keys(Task::DAFTAR_STATUS))],
            'penerima_tugas'    => ['required', 'array', 'min:1'],
            'penerima_tugas.*'  => ['integer', Rule::in($daftarIdCalonPenerima)],
            'file_task'         => ['nullable', 'array'],
            'file_task.*'       => ['file', 'max:10240'], // 10 MB per file
            'hapus_file'   => ['nullable', 'array'],
            'hapus_file.*' => ['integer'],
        ], [
            'penerima_tugas.required'  => 'Pilih minimal satu penerima tugas.',
            'penerima_tugas.*.in'      => 'Penerima tugas tidak sesuai dengan hak akses Anda.',
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

    private function hapusFileTerpilih(Request $request, Task $task): void
    {
        $daftarIdFileDihapus = $request->input('hapus_file', []);

        if (empty($daftarIdFileDihapus)) {
            return;
        }

        $task->files()
            ->whereIn('id_file_task', $daftarIdFileDihapus)
            ->get()
            ->each(function (FileTask $fileTask) {
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

    private function susunDetailTask(Task $task, int $idUserLogin): array
    {
        return [
            'namaProgram'    => $task->program->nama_program ?? '-',
            'judulTugas' => $task->judul_tugas,
            'deskripsiTugas' => $task->deskripsi_tugas,
            'tenggatWaktu'   => $task->tenggat_waktu->format('d M Y'),
            'pemberiTugas'   => $task->pemberiTugas->nama ?? '-',
            'daftarPenerima' => $task->penerimaTugas->pluck('nama')->all(),
            'labelStatus'    => Task::DAFTAR_STATUS[$task->status],
            'daftarFile'     => $task->files->map(fn(FileTask $fileTask) => [
                'namaFile' => $fileTask->nama_file,
                'urlFile'  => asset('storage/' . $fileTask->path_file),
            ])->all(),
            'urlEdit'        => $task->apakahPemberiTugas($idUserLogin)
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
            'title' => Str::limit($task->judul_tugas, 40),
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
