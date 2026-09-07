<?php

namespace App\Http\Controllers;

use App\Models\PicIndikator;
use App\Models\IndikatorProgram;
use App\Models\User;
use Illuminate\Http\Request;

class PicIndikatorController extends Controller
{
    public function index()
    {
        $picIndikators = PicIndikator::with(['indikatorProgram', 'user'])->paginate(10);
        return view('pic_indikator.index', compact('picIndikators'));
    }

    public function create()
    {
        $indikatorPrograms = IndikatorProgram::all();
        $users = User::all();
        return view('pic_indikator.create', compact('indikatorPrograms', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_indikator' => 'required|exists:indikator_program,id_indikator',
            'id_user' => 'required|exists:users,id_user',
        ]);

        PicIndikator::create($validated);

        return redirect()->route('pic-indikator.index')->with('success', 'PIC Indikator berhasil ditambahkan.');
    }

    public function edit(PicIndikator $picIndikator)
    {
        $indikatorPrograms = IndikatorProgram::all();
        $users = User::all();
        return view('pic_indikator.edit', compact('picIndikator', 'indikatorPrograms', 'users'));
    }

    public function update(Request $request, PicIndikator $picIndikator)
    {
        $validated = $request->validate([
            'id_indikator' => 'required|exists:indikator_program,id_indikator',
            'id_user' => 'required|exists:users,id_user',
        ]);

        $picIndikator->update($validated);

        return redirect()->route('pic-indikator.index')->with('success', 'PIC Indikator berhasil diperbarui.');
    }

    public function destroy(PicIndikator $picIndikator)
    {
        $picIndikator->delete();
        return redirect()->route('pic-indikator.index')->with('success', 'PIC Indikator berhasil dihapus.');
    }
}
