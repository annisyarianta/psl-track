<?php

namespace App\Http\Controllers;

use App\Models\IndikatorKegiatan;
use App\Models\Kegiatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class IndikatorKegiatanController extends Controller
{
    public function index()
    {
        $indikatorKegiatan = IndikatorKegiatan::with(
            'kegiatan.indikatorProgram.program.indikatorInisiatif'
        )
            ->orderByDesc('id_indikator_kegiatan')
            ->paginate(10);

        return view(
            'indikator-kegiatan.index',
            compact('indikatorKegiatan')
        );
    }

    public function create()
    {
        $kegiatan = Kegiatan::with(
            'indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_kegiatan')
            ->get();

        return view(
            'indikator-kegiatan.create',
            compact('kegiatan')
        );
    }

    public function store(Request $request)
    {
        $dataTervalidasi = $request->validate([
            'id_kegiatan' => [
                'required',
                'exists:kegiatan,id_kegiatan',
            ],

            'nama_indikator_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],

            'target_asmen' => [
                'nullable',
                'string',
                'max:255',
            ],

            'id_user'   => ['nullable', 'array'],
            'id_user.*' => [
                'distinct',
                Rule::exists('users', 'id_user')->where(
                    fn($query) => $query
                        ->where('role', 'staff')
                        ->where('id_unit', auth()->user()->id_unit)
                ),
            ],
        ]);

        DB::transaction(function () use ($dataTervalidasi) {
            $indikatorKegiatan = IndikatorKegiatan::create(Arr::except($dataTervalidasi, 'id_user'));
            $this->sinkronkanPicStaff($indikatorKegiatan, $dataTervalidasi['id_user'] ?? []);
        });

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Indikator kegiatan berhasil ditambahkan.');

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Indikator kegiatan berhasil ditambahkan.');
    }

    public function show(IndikatorKegiatan $indikatorKegiatan)
    {
        $indikatorKegiatan->load([
            'kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'subKegiatan',
            'picStaff',
        ]);

        return view(
            'indikator-kegiatan.show',
            compact('indikatorKegiatan')
        );
    }

    public function edit(IndikatorKegiatan $indikatorKegiatan)
    {
        $kegiatan = Kegiatan::with(
            'indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_kegiatan')
            ->get();

        return view(
            'indikator-kegiatan.edit',
            compact('indikatorKegiatan', 'kegiatan')
        );
    }

    public function update(Request $request, IndikatorKegiatan $indikatorKegiatan)
    {
        $dataTervalidasi = $request->validate([
            'nama_indikator_kegiatan' => ['required', 'string', 'max:255'],
            'target_asmen'            => ['nullable', 'string', 'max:255'],
            'id_staff'                => ['nullable', 'array'],
            'id_staff.*'              => [
                'distinct',
                Rule::exists('users', 'id_user')->where(
                    fn($query) => $query
                        ->where('role', 'staff')
                        ->where('id_unit', auth()->user()->id_unit)
                ),
            ],
        ]);

        DB::transaction(function () use ($indikatorKegiatan, $dataTervalidasi) {
            $indikatorKegiatan->update(Arr::except($dataTervalidasi, 'id_staff'));
            $this->sinkronkanPicStaff($indikatorKegiatan, $dataTervalidasi['id_staff'] ?? []);
        });

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Indikator kegiatan berhasil diperbarui.');
    }

    public function destroy(IndikatorKegiatan $indikatorKegiatan)
    {
        if ($indikatorKegiatan->subKegiatan()->exists()) {
            return redirect()
                ->route('kegiatan.index')
                ->with('error_hapus', 'Indikator kegiatan tidak dapat dihapus karena masih memiliki turunan.');
        }
        
        DB::transaction(function () use ($indikatorKegiatan) {
            $indikatorKegiatan->picStaff()->delete();
            $indikatorKegiatan->delete();
        });

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Indikator kegiatan berhasil dihapus.');
    }


    private function sinkronkanPicStaff(IndikatorKegiatan $indikatorKegiatan, array $daftarIdStaffBaru): void
    {
        $daftarIdStaffLama = $indikatorKegiatan->picStaff()->pluck('id_user')->all();

        $daftarIdStaffDihapus = array_diff($daftarIdStaffLama, $daftarIdStaffBaru);
        $daftarIdStaffDitambah = array_diff($daftarIdStaffBaru, $daftarIdStaffLama);

        if ($daftarIdStaffDihapus) {
            $indikatorKegiatan->picStaff()->whereIn('id_user', $daftarIdStaffDihapus)->delete();
        }

        foreach ($daftarIdStaffDitambah as $idStaff) {
            $indikatorKegiatan->picStaff()->create([
                'id_user'     => $idStaff,
                'assigned_by' => auth()->user()->id_user,
                'assigned_at' => now(),
            ]);
        }
    }
}
