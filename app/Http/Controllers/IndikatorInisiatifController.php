<?php

namespace App\Http\Controllers;

use App\Models\IndikatorInisiatif;
use App\Models\SasaranInisiatif;
use Illuminate\Http\Request;

class IndikatorInisiatifController extends Controller
{
    public function index()
    {
        $indikatorInisiatif = IndikatorInisiatif::with(
            'sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_indikator_inisiatif')
            ->paginate(10);

        return view(
            'indikator-inisiatif.index',
            compact('indikatorInisiatif')
        );
    }

    public function create()
    {
        $sasaranInisiatif = SasaranInisiatif::with(
            'indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_sasaran_inisiatif')
            ->get();

        return view(
            'indikator-inisiatif.create',
            compact('sasaranInisiatif')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sasaran_inisiatif' => [
                'required',
                'exists:sasaran_inisiatif,id_sasaran_inisiatif',
            ],

            'target_dirbag' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nama_indikator_inisiatif' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        IndikatorInisiatif::create($validated);

        return redirect()
            ->route('indikator-inisiatif.index')
            ->with('success', 'Indikator inisiatif berhasil ditambahkan.');
    }

    public function show(IndikatorInisiatif $indikatorInisiatif)
    {
        $indikatorInisiatif->load([
            'sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'program',
        ]);

        return view(
            'indikator-inisiatif.show',
            compact('indikatorInisiatif')
        );
    }

    public function edit(IndikatorInisiatif $indikatorInisiatif)
    {
        $sasaranInisiatif = SasaranInisiatif::with(
            'indikatorKpi.sasaranStrategis.tahun'
        )->get();

        return view(
            'indikator-inisiatif.edit',
            compact('indikatorInisiatif', 'sasaranInisiatif')
        );
    }

    public function update(
        Request $request,
        IndikatorInisiatif $indikatorInisiatif
    ) {
        $validated = $request->validate([
            'id_sasaran_inisiatif' => [
                'required',
                'exists:sasaran_inisiatif,id_sasaran_inisiatif',
            ],

            'target_dirbag' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nama_indikator_inisiatif' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $indikatorInisiatif->update($validated);

        return redirect()
            ->route('indikator-inisiatif.index')
            ->with('success', 'Indikator inisiatif berhasil diperbarui.');
    }

    public function destroy(IndikatorInisiatif $indikatorInisiatif)
    {
        $indikatorInisiatif->delete();

        return redirect()
            ->route('indikator-inisiatif.index')
            ->with('success', 'Indikator inisiatif berhasil dihapus.');
    }
}