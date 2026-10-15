<?php

namespace App\Http\Controllers;

use App\Models\IndikatorProgram;
use App\Models\Program;
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
        IndikatorProgram $indikatorProgram
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
                    'triwulan',
                    'semester',
                    'tahunan',
                ]),
            ],
            'id_unit'   => ['nullable', 'array'],
            'id_unit.*' => ['exists:unit,id_unit'],
        ]);

        DB::transaction(function () use ($indikatorProgram, $dataTervalidasi) {
            $indikatorProgram->update(Arr::except($dataTervalidasi, 'id_unit'));
            $this->sinkronkanPicUnit($indikatorProgram, $dataTervalidasi['id_unit'] ?? []);
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
}
