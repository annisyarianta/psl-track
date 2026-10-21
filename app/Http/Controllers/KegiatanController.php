<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Program;
use App\Models\Kegiatan;
use App\Models\IndikatorProgram;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    public function index()
    {
        $programs = Program::with(['indikatorProgram' => function ($q) {
            $q->withCount('kegiatan');
        }])->get();

        $kegiatan = Kegiatan::with(
            'indikatorProgram.program.indikatorInisiatif'
        )
            ->orderByDesc('id_kegiatan')
            ->paginate(10);

        $idUnitAsmen = auth()->user()->id_unit;

        $daftarStaff = $idUnitAsmen
            ? User::where('role', 'staff')
            ->where('id_unit', $idUnitAsmen)
            ->orderBy('nama')
            ->get()
            : collect();

        return view(
            'kegiatan.index',
            compact('kegiatan', 'programs', 'daftarStaff')
        );
    }

    public function byProgram($id_indikator_program)
    {
        $daftarKegiatan = Kegiatan::where('id_indikator_program', $id_indikator_program)
            ->with(['indikatorKegiatan' => function ($query) {
                $query->withCount('subKegiatan')->with('picStaff.user');
            }])
            ->get();

        return response()->json($daftarKegiatan);
    }

    public function create()
    {
        $indikatorProgram = IndikatorProgram::with(
            'program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_indikator_program')
            ->get();

        return view(
            'kegiatan.create',
            compact('indikatorProgram')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_indikator_program' => [
                'required',
                'exists:indikator_program,id_indikator_program',
            ],

            'nama_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        Kegiatan::create($validated);

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil ditambahkan.');
    }

    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->load([
            'indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'indikatorKegiatan',
        ]);

        return view(
            'kegiatan.show',
            compact('kegiatan')
        );
    }

    public function edit(Kegiatan $kegiatan)
    {
        $indikatorProgram = IndikatorProgram::with(
            'program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun'
        )
            ->orderByDesc('id_indikator_program')
            ->get();

        return view(
            'kegiatan.edit',
            compact('kegiatan', 'indikatorProgram')
        );
    }

    public function update(
        Request $request,
        Kegiatan $kegiatan
    ) {
        $validated = $request->validate([
            'nama_kegiatan' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $kegiatan->update($validated);

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan)
    {
        if ($kegiatan->indikatorKegiatan()->exists()) {
            return redirect()
                ->route('kegiatan.index')
                ->with('error_hapus', 'Kegiatan tidak dapat dihapus karena masih memiliki indikator kegiatan turunan.');
        }
        
        $kegiatan->delete();

        return redirect()
            ->route('kegiatan.index')
            ->with('success', 'Kegiatan berhasil dihapus.');
    }
}