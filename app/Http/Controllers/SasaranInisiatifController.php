<?php

namespace App\Http\Controllers;

use App\Models\SasaranInisiatif;
use App\Models\SasaranStrategis;
use App\Models\IndikatorKpi;
use Illuminate\Http\Request;

class SasaranInisiatifController extends Controller
{
    public function index()
    {
        $sasaranStrategis = SasaranStrategis::with([
            'indikatorKpi' => function ($query) {
                $query->withCount('sasaranInisiatif');
            }
        ])->get();
        $sasaranInisiatif = SasaranInisiatif::with(
            'indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_sasaran_inisiatif')
            ->paginate(10);

        return view(
            'sasaran_inisiatif.index',
            compact('sasaranInisiatif', 'sasaranStrategis')
        );
    }


    public function byKpi($id_indikator_kpi)
    {
        $sasaranInisiatif = SasaranInisiatif::where(
            'id_indikator_kpi',
            $id_indikator_kpi
        )
            ->with([
                'indikatorInisiatif' => function ($query) {
                    $query->withCount('programs');
                }
            ])
            ->orderBy('id_sasaran_inisiatif')
            ->get();

        return response()->json($sasaranInisiatif);
    }

    public function create()
    {
        $indikatorKpi = IndikatorKpi::with(
            'sasaranStrategis.tahun'
        )
            ->orderByDesc('id_indikator_kpi')
            ->get();

        return view(
            'sasaran-inisiatif.create',
            compact('indikatorKpi')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_indikator_kpi' => [
                'required',
                'exists:indikator_kpi,id_indikator_kpi',
            ],

            'nama_sasaran_inisiatif' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        SasaranInisiatif::create($validated);

        return redirect()
            ->route('sasaran-inisiatif.index')
            ->with('success', 'Sasaran inisiatif berhasil ditambahkan.');
    }

    public function show(SasaranInisiatif $sasaranInisiatif)
    {
        $sasaranInisiatif->load([
            'indikatorKpi.sasaranStrategis.tahun',
            'indikatorInisiatif',
        ]);

        return view(
            'sasaran-inisiatif.show',
            compact('sasaranInisiatif')
        );
    }

    public function edit(SasaranInisiatif $sasaranInisiatif)
    {
        $indikatorKpi = IndikatorKpi::with(
            'sasaranStrategis.tahun'
        )->get();

        return view(
            'sasaran-inisiatif.edit',
            compact('sasaranInisiatif', 'indikatorKpi')
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nama_sasaran_inisiatif' => ['required', 'string', 'max:255'],
        ]);

        SasaranInisiatif::findOrFail($id)->update($validated);

        return redirect()
            ->route('sasaran-inisiatif.index')
            ->with('success', 'Sasaran inisiatif berhasil diperbarui.');
    }

    public function destroy(SasaranInisiatif $sasaranInisiatif)
{
    if ($sasaranInisiatif->indikatorInisiatif()->exists()) {
        return redirect()
            ->route('sasaran-inisiatif.index')
            ->with('error_hapus', 'Sasaran inisiatif tidak dapat dihapus karena masih memiliki indikator inisiatif turunan.');
    }

    $sasaranInisiatif->delete();

    return redirect()
        ->route('sasaran-inisiatif.index')
        ->with('success', 'Sasaran inisiatif berhasil dihapus.');
}
}
