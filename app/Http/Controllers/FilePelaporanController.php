<?php

namespace App\Http\Controllers;

use App\Models\FilePelaporan;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class FilePelaporanController extends Controller
{
    public function index()
    {
        $files = FilePelaporan::with([
            'monitoring',
            'uploadedBy',
        ])
            ->orderByDesc('id_file')
            ->paginate(10);

        return view(
            'file-pelaporan.index',
            compact('files')
        );
    }

    public function create()
    {
        $monitorings = Monitoring::with([
            'periodeTw.tahun',
            'indikatorSubKegiatan',
        ])
            ->orderByDesc('id_monitoring')
            ->get();

        return view(
            'file-pelaporan.create',
            compact('monitorings')
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_monitoring' => [
                'required',
                'exists:monitoring,id_monitoring',
            ],

            'file' => [
                'required',
                'file',
                'max:10240',
            ],
        ]);

        $file = $request->file('file');

        $path = $file->store(
            'pelaporan',
            'public'
        );

        FilePelaporan::create([
            'id_monitoring' => $validated['id_monitoring'],
            'nama_file' => $file->getClientOriginalName(),
            'path_file' => $path,
            'uploaded_by' => Auth::id(),
            'uploaded_at' => now(),
        ]);

        return redirect()
            ->route(
                'monitoring.show',
                $validated['id_monitoring']
            )
            ->with(
                'success',
                'File pelaporan berhasil diupload.'
            );
    }

    public function show(FilePelaporan $filePelaporan)
    {
        $filePelaporan->load([
            'monitoring',
            'uploadedBy',
        ]);

        return view(
            'file-pelaporan.show',
            compact('filePelaporan')
        );
    }

    public function edit(FilePelaporan $filePelaporan)
    {
        $monitorings = Monitoring::with([
            'periodeTw.tahun',
            'indikatorSubKegiatan',
        ])->get();

        return view(
            'file-pelaporan.edit',
            compact(
                'filePelaporan',
                'monitorings'
            )
        );
    }

    public function update(
        Request $request,
        FilePelaporan $filePelaporan
    ) {
        $validated = $request->validate([
            'file' => [
                'nullable',
                'file',
                'max:10240',
            ],
        ]);

        if ($request->hasFile('file')) {
            // Hapus file lama
            if (
                $filePelaporan->path_file &&
                Storage::disk('public')->exists(
                    $filePelaporan->path_file
                )
            ) {
                Storage::disk('public')->delete(
                    $filePelaporan->path_file
                );
            }

            $file = $request->file('file');

            $path = $file->store(
                'pelaporan',
                'public'
            );

            $filePelaporan->update([
                'nama_file' => $file->getClientOriginalName(),
                'path_file' => $path,
                'uploaded_by' => Auth::id(),
                'uploaded_at' => now(),
            ]);
        }

        return redirect()
            ->route(
                'monitoring.show',
                $filePelaporan->id_monitoring
            )
            ->with(
                'success',
                'File pelaporan berhasil diperbarui.'
            );
    }

    public function destroy(FilePelaporan $filePelaporan)
    {
        if (
            $filePelaporan->path_file &&
            Storage::disk('public')->exists(
                $filePelaporan->path_file
            )
        ) {
            Storage::disk('public')->delete(
                $filePelaporan->path_file
            );
        }

        $monitoringId = $filePelaporan->id_monitoring;

        $filePelaporan->delete();

        return redirect()
            ->route(
                'monitoring.show',
                $monitoringId
            )
            ->with(
                'success',
                'File pelaporan berhasil dihapus.'
            );
    }
}