<?php

namespace App\Http\Controllers;

use App\Models\PicStaff;
use App\Models\IndikatorKegiatan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PicStaffController extends Controller
{
    public function index()
    {
        $picStaffs = PicStaff::with([
            'indikatorKegiatan.kegiatan',
            'user',
            'assignedBy',
        ])
            ->orderByDesc('id_pic_staff')
            ->paginate(10);

        return view(
            'pic-staff.index',
            compact('picStaffs')
        );
    }

    public function create()
    {
        $indikatorKegiatan = IndikatorKegiatan::with([
            'kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
        ])
            ->orderByDesc('id_indikator_kegiatan')
            ->get();

        $users = User::where('role', 'staff')
            ->orderBy('nama')
            ->get();

        return view(
            'pic-staff.create',
            compact(
                'indikatorKegiatan',
                'users'
            )
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_indikator_kegiatan' => [
                'required',
                'exists:indikator_kegiatan,id_indikator_kegiatan',
            ],

            'id_user' => [
                'required',
                'exists:users,id_user',
            ],
        ]);

        $validated['assigned_by'] = Auth::id();
        $validated['assigned_at'] = now();

        PicStaff::create($validated);

        return redirect()
            ->route('pic-staff.index')
            ->with(
                'success',
                'PIC Staff berhasil ditambahkan.'
            );
    }

    public function show(PicStaff $picStaff)
    {
        $picStaff->load([
            'indikatorKegiatan.kegiatan.indikatorProgram.program',
            'user',
            'assignedBy',
        ]);

        return view(
            'pic-staff.show',
            compact('picStaff')
        );
    }

    public function edit(PicStaff $picStaff)
    {
        $indikatorKegiatan = IndikatorKegiatan::with([
            'kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun',
        ])->get();

        $users = User::where('role', 'staff')
            ->orderBy('nama')
            ->get();

        return view(
            'pic-staff.edit',
            compact(
                'picStaff',
                'indikatorKegiatan',
                'users'
            )
        );
    }

    public function update(
        Request $request,
        PicStaff $picStaff
    ) {
        $validated = $request->validate([
            'id_indikator_kegiatan' => [
                'required',
                'exists:indikator_kegiatan,id_indikator_kegiatan',
            ],

            'id_user' => [
                'required',
                'exists:users,id_user',
            ],
        ]);

        $picStaff->update($validated);

        return redirect()
            ->route('pic-staff.index')
            ->with(
                'success',
                'PIC Staff berhasil diperbarui.'
            );
    }

    public function destroy(PicStaff $picStaff)
    {
        $picStaff->delete();

        return redirect()
            ->route('pic-staff.index')
            ->with(
                'success',
                'PIC Staff berhasil dihapus.'
            );
    }
}