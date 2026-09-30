<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Models\IndikatorProgram;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $kegiatan = Kegiatan::with(
            'indikatorProgram.program.indikatorInisiatif'
        )
            ->orderByDesc('id_kegiatan')
            ->paginate(10);

        return view(
            'kegiatan.index',
            compact('kegiatan')
        );
    }

    public function create()
    {
        $indikatorProgram = IndikatorProgram::with(
            'program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_indikator_program')
            ->get();

        return view(
            'kegiatan.create',
            compact('indikatorProgram')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_indikator_program' => [
                'required',
                'exists:indikator_program,id_indikator_program',
            ],

            'nama_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        Kegiatan::create($validated);

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load([
            'indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'indikatorKegiatan',
        ]);

        return view(
            'kegiatan.show',
            compact('kegiatan')
        );
    }

    public function edit(Kegiatan $kegiatan)
    {
        $indikatorProgram = IndikatorProgram::with(
            'program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_indikator_program')
            ->get();

        return view(
            'kegiatan.edit',
            compact('kegiatan', 'indikatorProgram')
        );
    }

    public function update(
        Request $request,
        Kegiatan $kegiatan
    ) {
        $validated = $request->validate([
            'id_indikator_program' => [
                'required',
                'exists:indikator_program,id_indikator_program',
            ],

            'nama_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $kegiatan->update($validated);

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        $kegiatan->delete();

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }

    public function indikator(Kegiatan $kegiatan)
    {
        $kegiatan->load([
            'indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'indikatorKegiatan.subKegiatan.indikatorSubKegiatan',
        ]);

        return view(
            'kegiatan.indikator',
            compact('kegiatan')
        );
    }
}