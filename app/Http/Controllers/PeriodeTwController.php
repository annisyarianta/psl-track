<?php

namespace App\Http\Controllers;

use App\Models\PeriodeTw;
use App\Models\Tahun;
use Illuminate\Http\Request;

class PeriodeTwController extends Controller
{
    public function index()
    {
        $periodeTws = PeriodeTw::with('tahun')
            ->orderByDesc('id_tahun')
            ->orderBy('triwulan')
            ->paginate(10);

        return view(
            'periode-tw.index',
            compact('periodeTws')
        );
    }

    public function create()
    {
        $tahun = Tahun::orderByDesc('tahun')->get();

        return view(
            'periode-tw.create',
            compact('tahun')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tahun' => [
                'required',
                'exists:tahun,id_tahun',
            ],

            'triwulan' => [
                'required',
                'integer',
                'between:1,4',
            ],
        ]);

        PeriodeTw::create($validated);

        return redirect()
            ->route('periode-tw.index')
            ->with(
                'success',
                'Periode triwulan berhasil ditambahkan.'
            );
    }

    public function show(PeriodeTw $periodeTw)
    {
        $periodeTw->load([
            'tahun',
            'monitoring.indikatorSubKegiatan',
        ]);

        return view(
            'periode-tw.show',
            compact('periodeTw')
        );
    }

    public function edit(PeriodeTw $periodeTw)
    {
        $tahun = Tahun::orderByDesc('tahun')->get();

        return view(
            'periode-tw.edit',
            compact(
                'periodeTw',
                'tahun'
            )
        );
    }

    public function update(
        Request $request,
        PeriodeTw $periodeTw
    ) {
        $validated = $request->validate([
            'id_tahun' => [
                'required',
                'exists:tahun,id_tahun',
            ],

            'triwulan' => [
                'required',
                'integer',
                'between:1,4',
            ],
        ]);

        $periodeTw->update($validated);

        return redirect()
            ->route('periode-tw.index')
            ->with(
                'success',
                'Periode triwulan berhasil diperbarui.'
            );
    }

    public function destroy(PeriodeTw $periodeTw)
    {
        $periodeTw->delete();

        return redirect()
            ->route('periode-tw.index')
            ->with(
                'success',
                'Periode triwulan berhasil dihapus.'
            );
    }
}