<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use App\Models\PeriodeTw;
use App\Models\IndikatorSubKegiatan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class MonitoringController extends Controller
{
    public function index()
    {
        $monitorings = Monitoring::with([
            'periodeTw.tahun',
            'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.kegiatan',
            'lastUpdatedBy',
            'files',
        ])
            ->orderByDesc('id_monitoring')
            ->paginate(10);

        return view(
            'monitoring.index',
            compact('monitorings')
        );
    }

    public function create()
    {
        $periodeTws = PeriodeTw::with('tahun')
            ->orderByDesc('id_tahun')
            ->orderBy('triwulan')
            ->get();

        $indikatorSubKegiatan = IndikatorSubKegiatan::with([
            'subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
        ])
            ->orderByDesc('id_indikator_sub_kegiatan')
            ->get();

        return view(
            'monitoring.create',
            compact(
                'periodeTws',
                'indikatorSubKegiatan'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_periode_tw' => [
                'required',
                'exists:periode_tw,id_periode_tw',
            ],

            'id_indikator_sub_kegiatan' => [
                'required',
                'exists:indikator_sub_kegiatan,id_indikator_sub_kegiatan',
            ],

            'upaya' => [
                'required',
                'string',
                'max:255',
            ],

            'capaian' => [
                'required',
                'string',
                'max:255',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'identifikasi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'notstarted',
                    'onprogress',
                    'done',
                ]),
            ],
        ]);

        $validated['last_updated_by'] = Auth::id();
        $validated['last_updated_at'] = now();

        Monitoring::create($validated);

        return redirect()
            ->route('monitoring.index')
            ->with(
                'success',
                'Data monitoring berhasil ditambahkan.'
            );
    }

    public function show(Monitoring $monitoring)
    {
        $monitoring->load([
            'periodeTw.tahun',
            'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram',
            'lastUpdatedBy',
            'files.uploadedBy',
        ]);

        return view(
            'monitoring.show',
            compact('monitoring')
        );
    }

    public function edit(Monitoring $monitoring)
    {
        $monitoring->load([
            'periodeTw.tahun',
            'indikatorSubKegiatan.subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram',
        ]);

        return view(
            'monitoring.edit',
            compact('monitoring')
        );
    }

    public function update(
        Request $request,
        Monitoring $monitoring
    ) {
        $validated = $request->validate([
            'upaya' => [
                'required',
                'string',
                'max:255',
            ],

            'capaian' => [
                'required',
                'string',
                'max:255',
            ],

            'keterangan' => [
                'nullable',
                'string',
                'max:255',
            ],

            'identifikasi' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'nullable',
                Rule::in([
                    'notstarted',
                    'onprogress',
                    'done',
                ]),
            ],
        ]);

        $validated['last_updated_by'] = Auth::id();
        $validated['last_updated_at'] = now();

        $monitoring->update($validated);

        return redirect()
            ->route('monitoring.show', $monitoring)
            ->with(
                'success',
                'Monitoring berhasil diperbarui.'
            );
    }

    public function destroy(Monitoring $monitoring)
    {
        $monitoring->delete();

        return redirect()
            ->route('monitoring.index')
            ->with(
                'success',
                'Data monitoring berhasil dihapus.'
            );
    }
}