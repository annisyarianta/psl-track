<?php

namespace App\Http\Controllers;

use App\Models\IndikatorProgram;
use App\Models\IndikatorSubKegiatan;
use App\Models\Monitoring;
use App\Models\Program;
use App\Services\PenyelarasMonitoring;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class IndikatorProgramController extends Controller
{
    public function index()
    {
        $indikatorProgram = IndikatorProgram::with(
            'program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_indikator_program')
            ->paginate(10);

        return view(
            'indikator-program.index',
            compact('indikatorProgram')
        );
    }

    public function create()
    {
        $program = Program::with(
            'indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_program')
            ->get();

        return view(
            'indikator-program.create',
            compact('program')
        );
    }

    public function store(Request $request)
    {
        $dataTervalidasi  = $request->validate([
            'id_program' => [
                'required',
                'exists:program,id_program',
            ],

            'nama_indikator_program' => [
                'required',
                'string',
                'max:255',
            ],

            'target_manager' => [
                'nullable',
                'string',
                'max:255',
            ],

            'aspek' => [
                'required',
                Rule::in(['kualitas', 'kuantitas']),
            ],

            'periode_pengukuran' => [
                'required',
                Rule::in([
                    'bulanan',
                    'triwulan',
                    'semester',
                    'tahunan',
                ]),
            ],
            'id_unit'   => ['nullable', 'array'],
            'id_unit.*' => ['exists:unit,id_unit'],
        ]);

        DB::transaction(function () use ($dataTervalidasi) {
            $indikatorProgram = IndikatorProgram::create(Arr::except($dataTervalidasi, 'id_unit'));
            $this->sinkronkanPicUnit($indikatorProgram, $dataTervalidasi['id_unit'] ?? []);
        });

        return redirect()
            ->route('program.index')
            ->with('success', 'Indikator program berhasil ditambahkan.');
    }

    public function show(IndikatorProgram $indikatorProgram)
    {
        $indikatorProgram->load([
            'program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'kegiatan',
            'picUnit.unit',
        ]);

        return view(
            'indikator-program.show',
            compact('indikatorProgram')
        );
    }

    public function edit(IndikatorProgram $indikatorProgram)
    {
        $program = Program::with(
            'indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )->get();

        return view(
            'indikator-program.edit',
            compact('indikatorProgram', 'program')
        );
    }

    public function update(
        Request $request,
        IndikatorProgram $indikatorProgram,
        PenyelarasMonitoring $penyelarasMonitoring
    ) {
        $dataTervalidasi  = $request->validate([
            'nama_indikator_program' => [
                'required',
                'string',
                'max:255',
            ],

            'target_manager' => [
                'nullable',
                'string',
                'max:255',
            ],

            'aspek' => [
                'required',
                Rule::in(['kualitas', 'kuantitas']),
            ],

            'periode_pengukuran' => [
                'required',
                Rule::in([
                    'bulanan',
                    'triwulan',
                    'semester',
                    'tahunan',
                ]),
            ],
            'id_unit'   => ['nullable', 'array'],
            'id_unit.*' => ['exists:unit,id_unit'],
        ]);

        $periodePengukuranBerubah = $indikatorProgram->periode_pengukuran !== $dataTervalidasi['periode_pengukuran'];

        // Periode pengukuran hanya boleh diubah jika belum ada monitoring yang terisi,
        // karena baris monitoring dibuat per periode (bulanan/triwulan/semester/tahunan).
        if ($periodePengukuranBerubah) {
            $jumlahMonitoringTerisi = $this->hitungMonitoringTerisi($indikatorProgram);

            if ($jumlahMonitoringTerisi > 0) {
                return back()
                    ->withErrors([
                        'periode_pengukuran' => "Periode pengukuran tidak dapat diubah karena sudah ada {$jumlahMonitoringTerisi} data monitoring yang terisi.",
                    ])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($indikatorProgram, $dataTervalidasi, $periodePengukuranBerubah, $penyelarasMonitoring) {
            $indikatorProgram->update(Arr::except($dataTervalidasi, 'id_unit'));
            $this->sinkronkanPicUnit($indikatorProgram, $dataTervalidasi['id_unit'] ?? []);

            if ($periodePengukuranBerubah) {
                $this->selaraskanMonitoring($indikatorProgram, $penyelarasMonitoring);
            }
        });

        return redirect()
            ->route('program.index')
            ->with('success', 'Indikator program berhasil diperbarui.');
    }

    public function destroy(IndikatorProgram $indikatorProgram)
    {
        if ($indikatorProgram->kegiatan()->exists()) {
            return redirect()
                ->route('program.index')
                ->with('error_hapus', 'Indikator program tidak dapat dihapus karena masih memiliki turunan.');
        }

        DB::transaction(function () use ($indikatorProgram) {
            $indikatorProgram->picUnit()->delete();
            $indikatorProgram->delete();
        });

        return redirect()
            ->route('program.index')
            ->with('success', 'Indikator program berhasil dihapus.');
    }

    private function sinkronkanPicUnit(IndikatorProgram $indikatorProgram, array $daftarIdUnitBaru): void
    {
        $daftarIdUnitLama = $indikatorProgram->picUnit()->pluck('id_unit')->all();

        $daftarIdUnitDihapus = array_diff($daftarIdUnitLama, $daftarIdUnitBaru);
        $daftarIdUnitDitambah = array_diff($daftarIdUnitBaru, $daftarIdUnitLama);

        if ($daftarIdUnitDihapus) {
            $indikatorProgram->picUnit()->whereIn('id_unit', $daftarIdUnitDihapus)->delete();
        }

        foreach ($daftarIdUnitDitambah as $idUnit) {
            $indikatorProgram->picUnit()->create([
                'id_unit'     => $idUnit,
                'assigned_by' => auth()->user()->id_user,
                'assigned_at' => now(),
            ]);
        }
    }

    /**
     * Jumlah baris monitoring yang sudah terisi pada seluruh indikator sub kegiatan
     * di bawah indikator program ini.
     */
    private function hitungMonitoringTerisi(IndikatorProgram $indikatorProgram): int
    {
        return Monitoring::whereHas(
            'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.kegiatan',
            function (Builder $queryKegiatan) use ($indikatorProgram) {
                $queryKegiatan->where('id_indikator_program', $indikatorProgram->id_indikator_program);
            }
        )
            ->get()
            ->filter(fn(Monitoring $barisMonitoring) => $barisMonitoring->sudahTerisi())
            ->count();
    }

    /**
     * Membuat ulang baris monitoring semua indikator sub kegiatan di bawah indikator program ini
     * agar sesuai dengan periode_pengukuran yang baru.
     */
    private function selaraskanMonitoring(
        IndikatorProgram $indikatorProgram,
        PenyelarasMonitoring $penyelarasMonitoring
    ): void {
        IndikatorSubKegiatan::whereHas(
            'subKegiatan.indikatorKegiatan.kegiatan',
            function (Builder $queryKegiatan) use ($indikatorProgram) {
                $queryKegiatan->where('id_indikator_program', $indikatorProgram->id_indikator_program);
            }
        )
            ->get()
            ->each(fn(IndikatorSubKegiatan $indikatorSubKegiatan) => $penyelarasMonitoring->selaraskan(
                $indikatorSubKegiatan,
                auth()->user()->id_user
            ));
    }
}
