<?php

namespace App\Http\Controllers;

use App\Models\SubKegiatan;
use App\Models\IndikatorKegiatan;
use Illuminate\Http\Request;

class SubKegiatanController extends Controller
{
    public function index()
    {
        $subKegiatan = SubKegiatan::with(
            'indikatorKegiatan.kegiatan.indikatorProgram'
        )
            ->orderByDesc('id_sub_kegiatan')
            ->paginate(10);

        return view(
            'sub-kegiatan.index',
            compact('subKegiatan')
        );
    }

    public function create()
    {
        $indikatorKegiatan = IndikatorKegiatan::with(
            'kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_indikator_kegiatan')
            ->get();

        return view(
            'sub-kegiatan.create',
            compact('indikatorKegiatan')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_indikator_kegiatan' => [
                'required',
                'exists:indikator_kegiatan,id_indikator_kegiatan',
            ],

            'nama_sub_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        SubKegiatan::create($validated);

        return redirect()
            ->route('sub-kegiatan.index')
            ->with('success', 'Sub kegiatan berhasil ditambahkan.');
    }

    public function show(SubKegiatan $subKegiatan)
    {
        $subKegiatan->load([
            'indikatorKegiatan.kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'indikatorSubKegiatan',
        ]);

        return view(
            'sub-kegiatan.show',
            compact('subKegiatan')
        );
    }

    public function edit(SubKegiatan $subKegiatan)
    {
        $indikatorKegiatan = IndikatorKegiatan::with(
            'kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_indikator_kegiatan')
            ->get();

        return view(
            'sub-kegiatan.edit',
            compact('subKegiatan', 'indikatorKegiatan')
        );
    }

    public function update(
        Request $request,
        SubKegiatan $subKegiatan
    ) {
        $validated = $request->validate([
            'id_indikator_kegiatan' => [
                'required',
                'exists:indikator_kegiatan,id_indikator_kegiatan',
            ],

            'nama_sub_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $subKegiatan->update($validated);

        return redirect()
            ->route('sub-kegiatan.index')
            ->with('success', 'Sub kegiatan berhasil diperbarui.');
    }

    public function destroy(SubKegiatan $subKegiatan)
    {
        $subKegiatan->delete();

        return redirect()
            ->route('sub-kegiatan.index')
            ->with('success', 'Sub kegiatan berhasil dihapus.');
    }

    public function indikator(
        SubKegiatan $subKegiatan
    ) {
        $subKegiatan->load([
            'indikatorKegiatan.kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'indikatorSubKegiatan',
        ]);

        return view(
            'sub-kegiatan.indikator',
            compact('subKegiatan')
        );
    }
}