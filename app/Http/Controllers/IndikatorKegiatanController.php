<?php

namespace App\Http\Controllers;

use App\Models\IndikatorKegiatan;
use App\Models\Kegiatan;
use Illuminate\Http\Request;

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
        $validated = $request->validate([
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
        ]);

        IndikatorKegiatan::create($validated);

        return redirect()
            ->route('indikator-kegiatan.index')
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

    public function update(
        Request $request,
        IndikatorKegiatan $indikatorKegiatan
    ) {
        $validated = $request->validate([
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
        ]);

        $indikatorKegiatan->update($validated);

        return redirect()
            ->route('indikator-kegiatan.index')
            ->with('success', 'Indikator kegiatan berhasil diperbarui.');
    }

    public function destroy(IndikatorKegiatan $indikatorKegiatan)
    {
        $indikatorKegiatan->delete();

        return redirect()
            ->route('indikator-kegiatan.index')
            ->with('success', 'Indikator kegiatan berhasil dihapus.');
    }

    public function subKegiatan(
        IndikatorKegiatan $indikatorKegiatan
    ) {
        $indikatorKegiatan->load([
            'kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'subKegiatan.indikatorSubKegiatan',
        ]);

        return view(
            'indikator-kegiatan.sub-kegiatan',
            compact('indikatorKegiatan')
        );
    }
}