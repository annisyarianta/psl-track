<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\SasaranProgram;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::with('sasaranProgram')->paginate(10);
        return view('program.index', compact('programs'));
    }

    public function create()
    {
        $sasaranPrograms = SasaranProgram::all();
        return view('program.create', compact('sasaranPrograms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_sasaran' => 'required|exists:sasaran_program,id_sasaran',
            'nama_program' => 'required|string|max:255',
        ]);

        Program::create($validated);

        return redirect()->route('program.index')->with('success', 'Program berhasil ditambahkan.');
    }

    public function edit(Program $program)
    {
        $sasaranPrograms = SasaranProgram::all();
        return view('program.edit', compact('program', 'sasaranPrograms'));
    }

    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'id_sasaran' => 'required|exists:sasaran_program,id_sasaran',
            'nama_program' => 'required|string|max:255',
        ]);

        $program->update($validated);

        return redirect()->route('program.index')->with('success', 'Program berhasil diperbarui.');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return redirect()->route('program.index')->with('success', 'Program berhasil dihapus.');
    }
}