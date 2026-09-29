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
use Illuminate\Validation\Rule;


class MonitoringController extends Controller
{
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

        return view('monitoring.create', compact(
            'indikatorProgram',
            'periodeTw',
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_periode_tw' => 'required|exists:periode_tw,id_periode_tw',
            'id_indikator' => 'required|exists:indikator_program,id_indikator',
            'capaian' => 'required|string|max:255',
            'keterangan' => 'nullable|string|max:255',
            'identifikasi' => 'nullable|string|max:255',
            'status' => 'nullable',
            Rule::in([
                'notstarted',
                'onprogress',
                'done',
            ]),
        ]);

        $validated['last_updated_by'] = auth()->id();
        $validated['last_updated_at'] = now();

        $monitoring = Monitoring::create($validated);

        return redirect()->route('indikator.monitoring', $monitoring->id_indikator)->with('success', 'Monitoring berhasil ditambahkan.');
    }

    public function edit(Monitoring $monitoring)
    {
        $periodeTw = PeriodeTw::findOrFail($monitoring->id_periode_tw);
        $indikatorProgram = IndikatorProgram::findOrFail(
            $monitoring->id_indikator
        );

        return view('monitoring.edit', compact(
            'monitoring',
            'periodeTw',
            'indikatorProgram'
        ));
    }

    public function update(Request $request, Monitoring $monitoring)
    {
        $validated = $request->validate([
            'id_periode_tw' => [
                'required',
                'exists:periode_tw,id_periode_tw',
            ],

            'id_indikator' => [
                'required',
                'exists:indikator_program,id_indikator',
            ],

            'capaian' => [
                'required',
                'string',
                'max:255',
            ],

            'identifikasi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                Rule::in([
                    'notstarted',
                    'onprogress',
                    'done',
                ]),
            ],

            'dokumen.*' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx',
                'max:10240',
            ],
        ]);

        $validated['last_updated_by'] = auth()->id();
        $validated['last_updated_at'] = now();
        unset($validated['dokumen']);
        $monitoring->update($validated);

        return redirect()
            ->route(
                'indikator.monitoring',
                $monitoring->id_indikator
            )
            ->with(
                'success',
                'Monitoring berhasil diperbarui.'
            );
    }

    public function destroy(Monitoring $monitoring)
    {
        $monitoring->delete();
        return redirect()->route('monitoring.index')->with('success', 'Monitoring berhasil dihapus.');
    }
}
