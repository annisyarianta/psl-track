<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\SasaranProgram;
use App\Models\Program;
use App\Models\PicIndikator;
use App\Models\Monitoring;
use App\Models\PeriodeTw;
use App\Models\IndikatorProgram;
use Illuminate\Http\Request;

class MonitoringController extends Controller
{
    private array $statusOptions = ['draft', 'submitted', 'verified'];

    public function index($id_indikator)
    {
        $indikatorProgram = IndikatorProgram::with([
            'program.sasaranProgram.kpi',
        ])->findOrFail($id_indikator);

        $program = $indikatorProgram->program;
        $sasaranProgram = $program?->sasaranProgram;
        $kpi = $sasaranProgram?->kpi;

        $picIndikators = $indikatorProgram->picIndikator()
            ->with('user')
            ->get();

        for ($i = 1; $i <= 4; $i++) {

            PeriodeTw::firstOrCreate([
                'id_kpi' => $kpi->id_kpi,
                'triwulan' => $i,
            ]);
        }

        $periodeTw = PeriodeTw::where('id_kpi', $kpi->id_kpi)
            ->orderBy('triwulan')
            ->get();

        $monitorings = Monitoring::with([
            'periodeTw',
            'filePelaporan',
            'updatedBy',
        ])
            ->where('id_indikator', $id_indikator)
            ->get()
            ->keyBy('id_periode_tw');

        return view('monitoring.index', compact(
            'kpi',
            'sasaranProgram',
            'program',
            'indikatorProgram',
            'picIndikators',
            'periodeTw',
            'monitorings'
        ));
    }

    public function create(Request $request)
    {
        $request->validate([
            'id_indikator' => 'required|integer|exists:indikator_program,id_indikator',
            'id_periode_tw' => 'required|integer|exists:periode_tw,id_periode_tw',
        ]);

        $indikatorProgram = IndikatorProgram::findOrFail(
            $request->id_indikator
        );

        $periodeTw = PeriodeTw::findOrFail(
            $request->id_periode_tw
        );

        $statusOptions = $this->statusOptions;

        return view('monitoring.create', compact(
            'indikatorProgram',
            'periodeTw',
            'statusOptions'
        ));
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
