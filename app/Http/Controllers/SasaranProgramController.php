<?php

namespace App\Http\Controllers;

use App\Models\SasaranProgram;
use App\Models\Kpi;
use Illuminate\Http\Request;

class SasaranProgramController extends Controller
{
    public function index()
    {
        $sasaranPrograms = SasaranProgram::with('kpi')->paginate(10);
        return view('sasaran_program.index', compact('sasaranPrograms'));
    }

    public function create()
    {
        $kpis = Kpi::all();
        return view('sasaran_program.create', compact('kpis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kpi' => 'required|exists:kpi,id_kpi',
            'nama_sasaran' => 'required|string|max:255',
        ]);

        SasaranProgram::create($validated);

        return redirect()->route('sasaran-program.index')->with('success', 'Sasaran Program berhasil ditambahkan.');
    }

    public function edit(SasaranProgram $sasaranProgram)
    {
        $kpis = Kpi::all();
        return view('sasaran_program.edit', compact('sasaranProgram', 'kpis'));
    }

    public function update(Request $request, SasaranProgram $sasaranProgram)
    {
        $validated = $request->validate([
            'id_kpi' => 'required|exists:kpi,id_kpi',
            'nama_sasaran' => 'required|string|max:255',
        ]);

        $sasaranProgram->update($validated);

        return redirect()->route('sasaran-program.index')->with('success', 'Sasaran Program berhasil diperbarui.');
    }

    public function destroy(SasaranProgram $sasaranProgram)
    {
        $sasaranProgram->delete();
        return redirect()->route('sasaran-program.index')->with('success', 'Sasaran Program berhasil dihapus.');
    }
}