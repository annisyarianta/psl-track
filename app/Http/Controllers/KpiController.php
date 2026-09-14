<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\SasaranProgram;
use App\Models\Program;
use App\Models\IndikatorProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        Kpi::create($validated);

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
