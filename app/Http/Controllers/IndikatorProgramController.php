<?php

namespace App\Http\Controllers;

use App\Models\IndikatorProgram;
use App\Models\Program;
use App\Models\User;
use App\Models\PicIndikator;
use Illuminate\Http\Request;

class IndikatorProgramController extends Controller
{
    // TODO enum: sesuaikan pilihan aspek
    private array $aspekOptions = ['Kualitas', 'Kuantitas'];

    public function index()
    {
        $indikatorPrograms = IndikatorProgram::with('program')->paginate(10);
        return view('indikator_program.index', compact('indikatorPrograms'));
    }

    public function create(Request $request)
    {
        $programs = Program::all();
        $aspekOptions = $this->aspekOptions;

        $users = User::all();

        $program = null;
        $kpi = null;

        if ($request->id_program) {
            $program = Program::with('sasaranProgram.kpi')
                ->findOrFail($request->id_program);

            $kpi = $program->sasaranProgram->kpi;
        }

        return view('indikator_program.create', compact(
            'programs',
            'aspekOptions',
            'users',
            'program',
            'kpi'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_program' => 'required|exists:program,id_program',
            'nama_indikator' => 'required|string|max:255',
            'target' => 'nullable|string|max:100',
            'aspek' => 'nullable|in:' . implode(',', $this->aspekOptions),
            'periode_pengukuran' => 'nullable|string|max:100',
            'upaya' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
        ]);

        $indikatorProgram = IndikatorProgram::create($validated);

        return redirect()->route('kpi.show',
        $indikatorProgram->program->sasaranProgram->id_kpi)->with('success', 'Indikator Program berhasil ditambahkan.');
    }

    public function edit(IndikatorProgram $indikatorProgram)
    {
        $programs = Program::all();
        $users = User::all();
        $aspekOptions = $this->aspekOptions;

        $kpi = $indikatorProgram
            ->program
            ->sasaranProgram
            ->kpi;

        $selectedPicIds = $indikatorProgram
            ->picIndikator
            ->pluck('id_user')
            ->toArray();

        return view('indikator_program.edit', compact(
            'indikatorProgram',
            'programs',
            'users',
            'aspekOptions',
            'kpi',
            'selectedPicIds'
        ));
    }

    public function update(Request $request, IndikatorProgram $indikatorProgram)
    {
        $validated = $request->validate([
            'id_program' => 'required|exists:program,id_program',
            'nama_indikator' => 'required|string|max:255',
            'target' => 'nullable|string|max:255',
            'aspek' => 'nullable|string|max:255',
            'periode_pengukuran' => 'nullable|string|max:255',
            'upaya' => 'nullable|string',
            'due_date' => 'nullable|date',
            'id_user' => 'required|array',
            'id_user.*' => 'distinct|exists:users,id_user',
        ]);

        // Update data indikator
        $indikatorProgram->update([
            'id_program' => $validated['id_program'],
            'nama_indikator' => $validated['nama_indikator'],
            'target' => $validated['target'] ?? null,
            'aspek' => $validated['aspek'] ?? null,
            'periode_pengukuran' => $validated['periode_pengukuran'] ?? null,
            'upaya' => $validated['upaya'] ?? null,
            'due_date' => $validated['due_date'] ?? null,
        ]);

        // Hapus PIC lama
        $indikatorProgram->picIndikator()->delete();

        // Simpan PIC baru
        foreach ($validated['id_user'] as $id_user) {
            $indikatorProgram->picIndikator()->create([
                'id_user' => $id_user,
            ]);
        }

        return redirect()
            ->route(
                'kpi.show',
                $indikatorProgram->program->sasaranProgram->id_kpi
            )
            ->with(
                'success',
                'Indikator Program berhasil diperbarui.'
            );
    }

    public function destroy(IndikatorProgram $indikatorProgram)
    {
        $indikatorProgram->delete();
        return redirect()->route('indikator-program.index')->with('success', 'Indikator Program berhasil dihapus.');
    }
}
