<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::withCount('users')
            ->orderBy('nama_unit')
            ->get();

        return view('unit.index', compact('units'));
    }

    public function create()
    {
        return view('unit.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_unit' => ['required', 'string', 'max:255'],
        ]);

        Unit::create($validated);

        return redirect()
            ->route('unit.index')
            ->with('success', 'Unit berhasil ditambahkan.');
    }

    public function show(Unit $unit)
    {
        $unit->load([
            'users',
            'picUnits',
        ]);

        return view('unit.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        return view('unit.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'nama_unit' => ['required', 'string', 'max:255'],
        ]);

        $unit->update($validated);

        return redirect()
            ->route('unit.index')
            ->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()
            ->route('unit.index')
            ->with('success', 'Unit berhasil dihapus.');
    }
}