<?php

namespace App\Http\Controllers;

use App\Models\Tahun;
use App\Models\PeriodeTw;
use Illuminate\Http\Request;

class TahunController extends Controller
{
    public function index()
    {
        $tahun = Tahun::orderBy('tahun', 'desc')->get();

        return view('tahun.index', compact('tahun'));
    }

    public function create()
    {
        return view('tahun.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => [
                'required',
                'integer',
                'min:2000',
                'max:2100',
                'unique:tahun,tahun',
            ],
        ]);

        $tahun = Tahun::create($validated);

        // Otomatis membuat Triwulan I - IV
        for ($i = 1; $i <= 4; $i++) {
            PeriodeTw::create([
                'id_tahun' => $tahun->id_tahun,
                'triwulan' => $i,
            ]);
        }

        return redirect()
            ->route('tahun.index')
            ->with('success', 'Tahun dan periode triwulan berhasil ditambahkan.');
    }

    public function show(Tahun $tahun)
    {
        $tahun->load([
            'sasaranStrategis',
            'periodeTw',
        ]);

        return view('tahun.show', compact('tahun'));
    }

    public function edit(Tahun $tahun)
    {
        return view('tahun.edit', compact('tahun'));
    }

    public function update(Request $request, Tahun $tahun)
    {
        $validated = $request->validate([
            'tahun' => ['required', 'integer', 'min:2000', 'max:2100'],
        ]);

        $tahun->update($validated);

        return redirect()
            ->route('tahun.index')
            ->with('success', 'Data tahun berhasil diperbarui.');
    }

    public function destroy(Tahun $tahun)
    {
        $tahun->delete();

        return redirect()
            ->route('tahun.index')
            ->with('success', 'Data tahun berhasil dihapus.');
    }
}
