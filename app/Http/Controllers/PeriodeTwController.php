<?php

namespace App\Http\Controllers;

use App\Models\PeriodeTw;
use App\Models\Kpi;
use Illuminate\Http\Request;

class PeriodeTwController extends Controller
{
    public function index()
    {
        $periodeTws = PeriodeTw::with('kpi')->paginate(10);
        return view('periode_tw.index', compact('periodeTws'));
    }

    public function create()
    {
        $kpis = Kpi::all();
        return view('periode_tw.create', compact('kpis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kpi' => 'required|exists:kpi,id_kpi',
            'triwulan' => 'required|integer|min:1|max:4',
        ]);

        PeriodeTw::create($validated);

        return redirect()->route('periode-tw.index')->with('success', 'Periode TW berhasil ditambahkan.');
    }

    public function edit(PeriodeTw $periodeTw)
    {
        $kpis = Kpi::all();
        return view('periode_tw.edit', compact('periodeTw', 'kpis'));
    }

    public function update(Request $request, PeriodeTw $periodeTw)
    {
        $validated = $request->validate([
            'id_kpi' => 'required|exists:kpi,id_kpi',
            'triwulan' => 'required|integer|min:1|max:4',
        ]);

        $periodeTw->update($validated);

        return redirect()->route('periode-tw.index')->with('success', 'Periode TW berhasil diperbarui.');
    }

    public function destroy(PeriodeTw $periodeTw)
    {
        $periodeTw->delete();
        return redirect()->route('periode-tw.index')->with('success', 'Periode TW berhasil dihapus.');
    }
}