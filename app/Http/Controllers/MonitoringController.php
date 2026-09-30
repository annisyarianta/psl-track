<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
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
                'nullable',
                'string',
                'max:255',
            ],

            'capaian' => [
                'nullable',
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
}