<?php

namespace App\Http\Controllers;

use App\Models\SasaranStrategis;
use App\Models\Tahun;
use Illuminate\Http\Request;

class SasaranStrategisController extends Controller
{
    public function index(Request $request)
    {
        $tahunDipilih = $request->input('tahun', now()->year);
        $semuaTahun = $tahunDipilih === 'semua';

        $sasaranStrategis = SasaranStrategis::with('tahun')
        ->filterTahun($tahunDipilih)
        ->orderBy('id_sasaran_strategis', 'desc')
        ->get();

        return view('sasaran_strategis.index', compact('sasaranStrategis', 'tahunDipilih', 'semuaTahun'));
    }

    public function create()
    {
        $tahun = Tahun::orderByDesc('tahun')->get();

        return view(
            'sasaran-strategis.create',
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

            'nama_sasaran_strategis' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        SasaranStrategis::create($validated);

        return redirect()
            ->route('sasaran-strategis.index')
            ->with('success', 'Sasaran strategis berhasil ditambahkan.');
    }

    public function show(SasaranStrategis $sasaranStrategis)
    {
        $sasaranStrategis->load([
            'tahun',
            'indikatorKpi',
        ]);

        return view(
            'sasaran-strategis.show',
            compact('sasaranStrategis')
        );
    }

    public function edit(SasaranStrategis $sasaranStrategis)
    {
        $tahun = Tahun::orderByDesc('tahun')->get();

        return view(
            'sasaran-strategis.edit',
            compact('sasaranStrategis', 'tahun')
        );
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_tahun' => 'required|exists:tahun,id_tahun',
            'nama_sasaran_strategis' => 'required|string',
        ]);

        $sasaran = SasaranStrategis::findOrFail($id);
        $sasaran->update($validated);

        return redirect()->route('sasaran-strategis.index')
            ->with('success', 'Sasaran strategis berhasil diperbarui.');
    }

    public function destroy(SasaranStrategis $sasaranStrategi)
    {
        if ($sasaranStrategi->indikatorKpi()->exists()) {
            return redirect()
                ->route('sasaran-strategis.index')
                ->with('error_hapus', 'Sasaran strategis tidak dapat dihapus karena masih memiliki indikator KPI turunan.');
        }

        $sasaranStrategi->delete();

        return redirect()
            ->route('sasaran-strategis.index')
            ->with('success', 'Sasaran strategis berhasil dihapus.');
    }
}
