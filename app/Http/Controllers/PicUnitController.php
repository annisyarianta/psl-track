<?php

namespace App\Http\Controllers;

use App\Models\PicUnit;
use App\Models\IndikatorProgram;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PicUnitController extends Controller
{
    public function index()
    {
        $picUnits = PicUnit::with([
            'indikatorProgram.program',
            'unit',
            'assignedBy',
        ])
            ->orderByDesc('id_pic_unit')
            ->paginate(10);

        return view(
            'pic-unit.index',
            compact('picUnits')
        );
    }

    public function create()
    {
        $indikatorProgram = IndikatorProgram::with([
            'program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
        ])
            ->orderByDesc('id_indikator_program')
            ->get();

        $units = Unit::orderBy('nama_unit')->get();

        $users = User::orderBy('nama')->get();

        return view(
            'pic-unit.create',
            compact(
                'indikatorProgram',
                'units',
                'users'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_indikator_program' => [
                'required',
                'exists:indikator_program,id_indikator_program',
            ],

            'id_unit' => [
                'required',
                'exists:unit,id_unit',
            ],
        ]);

        $validated['assigned_by'] = Auth::id();
        $validated['assigned_at'] = now();

        PicUnit::create($validated);

        return redirect()
            ->route('pic-unit.index')
            ->with(
                'success',
                'PIC Unit berhasil ditambahkan.'
            );
    }

    public function show(PicUnit $picUnit)
    {
        $picUnit->load([
            'indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
            'unit',
            'assignedBy',
        ]);

        return view(
            'pic-unit.show',
            compact('picUnit')
        );
    }

    public function edit(PicUnit $picUnit)
    {
        $indikatorProgram = IndikatorProgram::with([
            'program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
        ])->get();

        $units = Unit::orderBy('nama_unit')->get();

        return view(
            'pic-unit.edit',
            compact(
                'picUnit',
                'indikatorProgram',
                'units'
            )
        );
    }

    public function update(
        Request $request,
        PicUnit $picUnit
    ) {
        $validated = $request->validate([
            'id_indikator_program' => [
                'required',
                'exists:indikator_program,id_indikator_program',
            ],

            'id_unit' => [
                'required',
                'exists:unit,id_unit',
            ],
        ]);

        $picUnit->update($validated);

        return redirect()
            ->route('pic-unit.index')
            ->with(
                'success',
                'PIC Unit berhasil diperbarui.'
            );
    }

    public function destroy(PicUnit $picUnit)
    {
        $picUnit->delete();

        return redirect()
            ->route('pic-unit.index')
            ->with(
                'success',
                'PIC Unit berhasil dihapus.'
            );
    }
}
