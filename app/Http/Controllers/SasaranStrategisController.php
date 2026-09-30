<?php

namespace App\Http\Controllers;

use App\Models\SasaranStrategis;
use App\Models\Tahun;
use Illuminate\Http\Request;

class SasaranStrategisController extends Controller
{
    public function index()
    {
        $sasaranStrategis = SasaranStrategis::with('tahun')
            ->orderByDesc('id_sasaran_strategis')
            ->paginate(10);

        return view(
            'sasaran-strategis.index',
            compact('sasaranStrategis')
        );
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

    public function update(
        Request $request,
        SasaranStrategis $sasaranStrategis
    ) {
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

        $sasaranStrategis->update($validated);

        return redirect()
            ->route('sasaran-strategis.index')
            ->with('success', 'Sasaran strategis berhasil diperbarui.');
    }

    public function destroy(SasaranStrategis $sasaranStrategis)
    {
        $sasaranStrategis->delete();

        return redirect()
            ->route('sasaran-strategis.index')
            ->with('success', 'Sasaran strategis berhasil dihapus.');
    }
}