<?php

namespace App\Http\Controllers;

use App\Models\IndikatorProgram;
use App\Models\Program;
use Illuminate\Http\Request;

class IndikatorProgramController extends Controller
{
    // TODO enum: sesuaikan pilihan aspek
    private array $aspekOptions = ['Input', 'Proses', 'Output', 'Outcome'];

    public function index()
    {
        $indikatorPrograms = IndikatorProgram::with('program')->paginate(10);
        return view('indikator_program.index', compact('indikatorPrograms'));
    }

    public function create()
    {
        $programs = Program::all();
        $aspekOptions = $this->aspekOptions;
        return view('indikator_program.create', compact('programs', 'aspekOptions'));
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

        IndikatorProgram::create($validated);

        return redirect()->route('indikator-program.index')->with('success', 'Indikator Program berhasil ditambahkan.');
    }

    public function edit(IndikatorProgram $indikatorProgram)
    {
        $programs = Program::all();
        $aspekOptions = $this->aspekOptions;
        return view('indikator_program.edit', compact('indikatorProgram', 'programs', 'aspekOptions'));
    }

    public function update(Request $request, IndikatorProgram $indikatorProgram)
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

        $indikatorProgram->update($validated);

        return redirect()->route('indikator-program.index')->with('success', 'Indikator Program berhasil diperbarui.');
    }

    public function destroy(IndikatorProgram $indikatorProgram)
    {
        $indikatorProgram->delete();
        return redirect()->route('indikator-program.index')->with('success', 'Indikator Program berhasil dihapus.');
    }
}