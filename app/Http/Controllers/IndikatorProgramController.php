<?php

namespace App\Http\Controllers;

use App\Models\IndikatorProgram;
use App\Models\Program;
use Illuminate\Http\Request;
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
        $validated = $request->validate([
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
        ]);

        IndikatorProgram::create($validated);

        return redirect()
            ->route('indikator-program.index')
            ->with('success', 'Indikator program berhasil ditambahkan.');
    }

    public function show(IndikatorProgram $indikatorProgram)
    {
        $indikatorProgram->load([
            'program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'kegiatan',
            'picUnit',
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
        $validated = $request->validate([
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
        ]);

        $indikatorProgram->update($validated);

        return redirect()
            ->route('indikator-program.index')
            ->with('success', 'Indikator program berhasil diperbarui.');
    }

    public function destroy(IndikatorProgram $indikatorProgram)
    {
        $indikatorProgram->delete();

        return redirect()
            ->route('indikator-program.index')
            ->with('success', 'Indikator program berhasil dihapus.');
    }

    public function kegiatan(IndikatorProgram $indikatorProgram)
    {
        $indikatorProgram->load([
            'program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'kegiatan.indikatorKegiatan.subKegiatan.indikatorSubKegiatan',
        ]);

        return view(
            'indikator-program.kegiatan',
            compact('indikatorProgram')
        );
    }
}