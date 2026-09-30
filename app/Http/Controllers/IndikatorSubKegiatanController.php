<?php

namespace App\Http\Controllers;

use App\Models\IndikatorSubKegiatan;
use App\Models\SubKegiatan;
use App\Models\Monitoring;
use App\Models\PeriodeTw;
use Illuminate\Http\Request;

class IndikatorSubKegiatanController extends Controller
{
    public function index()
    {
        $indikatorSubKegiatan = IndikatorSubKegiatan::with(
            'subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram'
        )
            ->orderByDesc('id_indikator_sub_kegiatan')
            ->paginate(10);

        return view(
            'indikator-sub-kegiatan.index',
            compact('indikatorSubKegiatan')
        );
    }

    public function create()
    {
        $subKegiatan = SubKegiatan::with(
            'indikatorKegiatan.kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_sub_kegiatan')
            ->get();

        return view(
            'indikator-sub-kegiatan.create',
            compact('subKegiatan')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sub_kegiatan' => [
                'required',
                'exists:sub_kegiatan,id_sub_kegiatan',
            ],

            'nama_indikator_sub_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],

            'target_staff' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $indikatorSubKegiatan = IndikatorSubKegiatan::create($validated);

        $indikatorSubKegiatan->load(
            'subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        );

        $tahun = $indikatorSubKegiatan
            ->subKegiatan
            ->indikatorKegiatan
            ->kegiatan
            ->indikatorProgram
            ->program
            ->indikatorInisiatif
            ->sasaranInisiatif
            ->indikatorKpi
            ->sasaranStrategis
            ->tahun;

        $periodeTws = PeriodeTw::where(
            'id_tahun',
            $tahun->id_tahun
        )->get();

        foreach ($periodeTws as $periodeTw) {
            Monitoring::create([
                'id_periode_tw' => $periodeTw->id_periode_tw,
                'id_indikator_sub_kegiatan' => $indikatorSubKegiatan->id_indikator_sub_kegiatan,
                'status' => 'notstarted',
            ]);
        }

        return redirect()
            ->route('indikator-sub-kegiatan.index')
            ->with(
                'success',
                'Indikator sub kegiatan dan monitoring berhasil dibuat.'
            );
    }

    public function show(
        IndikatorSubKegiatan $indikatorSubKegiatan
    ) {
        $indikatorSubKegiatan->load([
            'subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'monitoring',
        ]);

        return view(
            'indikator-sub-kegiatan.show',
            compact('indikatorSubKegiatan')
        );
    }

    public function edit(
        IndikatorSubKegiatan $indikatorSubKegiatan
    ) {
        $subKegiatan = SubKegiatan::with(
            'indikatorKegiatan.kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_sub_kegiatan')
            ->get();

        return view(
            'indikator-sub-kegiatan.edit',
            compact(
                'indikatorSubKegiatan',
                'subKegiatan'
            )
        );
    }

    public function update(
        Request $request,
        IndikatorSubKegiatan $indikatorSubKegiatan
    ) {
        $validated = $request->validate([
            'id_sub_kegiatan' => [
                'required',
                'exists:sub_kegiatan,id_sub_kegiatan',
            ],

            'nama_indikator_sub_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],

            'target_staff' => [
                'nullable',
                'string',
                'max:255',
            ],
        ]);

        $indikatorSubKegiatan->update($validated);

        return redirect()
            ->route('indikator-sub-kegiatan.index')
            ->with(
                'success',
                'Indikator sub kegiatan berhasil diperbarui.'
            );
    }

    public function destroy(
        IndikatorSubKegiatan $indikatorSubKegiatan
    ) {
        $indikatorSubKegiatan->delete();

        return redirect()
            ->route('indikator-sub-kegiatan.index')
            ->with(
                'success',
                'Indikator sub kegiatan berhasil dihapus.'
            );
    }

    public function monitoring(
        IndikatorSubKegiatan $indikatorSubKegiatan
    ) {
        $indikatorSubKegiatan->load([
            'subKegiatan.indikatorKegiatan.kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'monitoring.periodeTw',
        ]);

        return view(
            'indikator-sub-kegiatan.monitoring',
            compact('indikatorSubKegiatan')
        );
    }
}
