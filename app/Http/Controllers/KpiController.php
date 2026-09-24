<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\PeriodeTw;
use Illuminate\Http\Request;

class KpiController extends Controller
{
    public function index()
    {
        $kpis = Kpi::orderByDesc('tahun')->paginate(10);
        return view('kpi.index', compact('kpis'));
    }

    public function show($id_kpi)
    {
        $kpi = Kpi::with([
            'sasaranProgram.program.indikatorProgram'
        ])->findOrFail($id_kpi);

        return view('kpi.show', compact('kpi'));
    }

    public function create()
    {
        return view('kpi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer',
            'judul_kpi' => 'required|string|max:255',
        ]);

        $kpi = Kpi::create($validated);

        $triwulans = [
            'TW I',
            'TW II',
            'TW III',
            'TW IV',
        ];

        foreach ($triwulans as $triwulan) {

            PeriodeTw::create([
                'id_kpi' => $kpi->id_kpi,
                'triwulan' => $triwulan,
            ]);
        }

        return redirect()->route('kpi.index')->with('success', 'KPI berhasil ditambahkan.');
    }

    public function edit(Kpi $kpi)
    {
        return view('kpi.edit', compact('kpi'));
    }

    public function update(Request $request, Kpi $kpi)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer',
            'judul_kpi' => 'required|string|max:255',
        ]);

        $kpi->update($validated);

        return redirect()->route('kpi.index')->with('success', 'KPI berhasil diperbarui.');
    }

    public function destroy(Kpi $kpi)
    {
        $kpi->delete();
        return redirect()->route('kpi.index')->with('success', 'KPI berhasil dihapus.');
    }
}
