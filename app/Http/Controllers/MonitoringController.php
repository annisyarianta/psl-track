<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use App\Models\PeriodeTw;
use App\Models\IndikatorProgram;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    // TODO enum: sesuaikan pilihan status
    private array $statusOptions = ['draft', 'submitted', 'verified'];

    public function index()
    {
        $monitorings = Monitoring::with(['periodeTw', 'indikatorProgram'])->paginate(10);
        return view('monitoring.index', compact('monitorings'));
    }

    public function create()
    {
        $periodeTws = PeriodeTw::all();
        $indikatorPrograms = IndikatorProgram::all();
        $statusOptions = $this->statusOptions;
        return view('monitoring.create', compact('periodeTws', 'indikatorPrograms', 'statusOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_periode_tw' => 'required|exists:periode_tw,id_periode_tw',
            'id_indikator' => 'required|exists:indikator_program,id_indikator',
            'capaian' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'identifikasi' => 'nullable|string|max:255',
            'status' => 'required|in:' . implode(',', $this->statusOptions),
        ]);

        // otomatis dari user yang sedang login
        $validated['last_updated_by'] = auth()->id();
        $validated['last_updated_at'] = now();

        Monitoring::create($validated);

        return redirect()->route('monitoring.index')->with('success', 'Monitoring berhasil ditambahkan.');
    }

    public function edit(Monitoring $monitoring)
    {
        $periodeTws = PeriodeTw::all();
        $indikatorPrograms = IndikatorProgram::all();
        $statusOptions = $this->statusOptions;
        return view('monitoring.edit', compact('monitoring', 'periodeTws', 'indikatorPrograms', 'statusOptions'));
    }

    public function update(Request $request, Monitoring $monitoring)
    {
        $validated = $request->validate([
            'id_periode_tw' => 'required|exists:periode_tw,id_periode_tw',
            'id_indikator' => 'required|exists:indikator_program,id_indikator',
            'capaian' => 'nullable|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'identifikasi' => 'nullable|string|max:255',
            'status' => 'required|in:' . implode(',', $this->statusOptions),
        ]);

        $validated['last_updated_by'] = auth()->id();
        $validated['last_updated_at'] = now();

        $monitoring->update($validated);

        return redirect()->route('monitoring.index')->with('success', 'Monitoring berhasil diperbarui.');
    }

    public function destroy(Monitoring $monitoring)
    {
        $monitoring->delete();
        return redirect()->route('monitoring.index')->with('success', 'Monitoring berhasil dihapus.');
    }
}
