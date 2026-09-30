<?php

namespace App\Http\Controllers;

use App\Models\IndikatorKpi;
use App\Models\SasaranStrategis;
use Illuminate\Http\Request;

class IndikatorKpiController extends Controller
{
    public function index()
    {
        $indikatorKpi = IndikatorKpi::with('sasaranStrategis.tahun')
            ->orderByDesc('id_indikator_kpi')
            ->paginate(10);

        return view(
            'indikator-kpi.index',
            compact('indikatorKpi')
        );
    }

    public function create()
    {
        $sasaranStrategis = SasaranStrategis::with('tahun')
            ->orderByDesc('id_sasaran_strategis')
            ->get();

        return view(
            'indikator-kpi.create',
            compact('sasaranStrategis')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sasaran_strategis' => [
                'required',
                'exists:sasaran_strategis,id_sasaran_strategis',
            ],

            'target_dirbag' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nama_indikator_kpi' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        IndikatorKpi::create($validated);

        return redirect()
            ->route('indikator-kpi.index')
            ->with('success', 'Indikator KPI berhasil ditambahkan.');
    }

    public function show(IndikatorKpi $indikatorKpi)
    {
        $indikatorKpi->load([
            'sasaranStrategis.tahun',
            'sasaranInisiatif',
        ]);

        return view(
            'indikator-kpi.show',
            compact('indikatorKpi')
        );
    }

    public function edit(IndikatorKpi $indikatorKpi)
    {
        $sasaranStrategis = SasaranStrategis::with('tahun')
            ->orderByDesc('id_sasaran_strategis')
            ->get();

        return view(
            'indikator-kpi.edit',
            compact('indikatorKpi', 'sasaranStrategis')
        );
    }

    public function update(
        Request $request,
        IndikatorKpi $indikatorKpi
    ) {
        $validated = $request->validate([
            'id_sasaran_strategis' => [
                'required',
                'exists:sasaran_strategis,id_sasaran_strategis',
            ],

            'target_dirbag' => [
                'nullable',
                'string',
                'max:255',
            ],

            'nama_indikator_kpi' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $indikatorKpi->update($validated);

        return redirect()
            ->route('indikator-kpi.index')
            ->with('success', 'Indikator KPI berhasil diperbarui.');
    }

    public function destroy(IndikatorKpi $indikatorKpi)
    {
        $indikatorKpi->delete();

        return redirect()
            ->route('indikator-kpi.index')
            ->with('success', 'Indikator KPI berhasil dihapus.');
    }

    public function struktur(IndikatorKpi $indikatorKpi)
    {
        $indikatorKpi->load([
            'sasaranStrategis.tahun',
            'sasaranInisiatif.indikatorInisiatif.program.indikatorProgram',
        ]);

        return view(
            'indikator-kpi.struktur',
            compact('indikatorKpi')
        );
    }
}