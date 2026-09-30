<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\IndikatorInisiatif;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $program = Program::with(
            'indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_program')
            ->paginate(10);

        return view(
            'program.index',
            compact('program')
        );
    }

    public function create()
    {
        $indikatorInisiatif = IndikatorInisiatif::with(
            'sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_indikator_inisiatif')
            ->get();

        return view(
            'program.create',
            compact('indikatorInisiatif')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_indikator_inisiatif' => [
                'required',
                'exists:indikator_inisiatif,id_indikator_inisiatif',
            ],

            'nama_program' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        Program::create($validated);

        return redirect()
            ->route('program.index')
            ->with('success', 'Program berhasil ditambahkan.');
    }

    public function show(Program $program)
    {
        $program->load([
            'indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'indikatorProgram',
        ]);

        return view(
            'program.show',
            compact('program')
        );
    }

    public function edit(Program $program)
    {
        $indikatorInisiatif = IndikatorInisiatif::with(
            'sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )->get();

        return view(
            'program.edit',
            compact('program', 'indikatorInisiatif')
        );
    }

    public function update(
        Request $request,
        Program $program
    ) {
        $validated = $request->validate([
            'id_indikator_inisiatif' => [
                'required',
                'exists:indikator_inisiatif,id_indikator_inisiatif',
            ],

            'nama_program' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $program->update($validated);

        return redirect()
            ->route('program.index')
            ->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();

        return redirect()
            ->route('program.index')
            ->with('success', 'Program berhasil dihapus.');
    }
}