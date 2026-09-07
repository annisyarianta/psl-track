<?php

namespace App\Http\Controllers;

use App\Models\FilePelaporan;
use App\Models\Monitoring;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FilePelaporanController extends Controller
{
    public function index()
    {
        $filePelaporans = FilePelaporan::with('monitoring')->paginate(10);
        return view('file_pelaporan.index', compact('filePelaporans'));
    }

    public function create()
    {
        $monitorings = Monitoring::all();
        return view('file_pelaporan.create', compact('monitorings'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_monitoring' => 'required|exists:monitoring,id_monitoring',
            'file' => 'required|file|max:10240', // maks 10MB
        ]);

        $path = $request->file('file')->store('file_pelaporan', 'public');

        FilePelaporan::create([
            'id_monitoring' => $validated['id_monitoring'],
            'nama_file' => $request->file('file')->getClientOriginalName(),
            'path_file' => $path,
            'uploaded_by' => auth()->id(),
            'uploaded_at' => now(),
        ]);

        return redirect()->route('file-pelaporan.index')->with('success', 'File berhasil diunggah.');
    }

    public function edit(FilePelaporan $filePelaporan)
    {
        $monitorings = Monitoring::all();
        return view('file_pelaporan.edit', compact('filePelaporan', 'monitorings'));
    }

    public function update(Request $request, FilePelaporan $filePelaporan)
    {
        $validated = $request->validate([
            'id_monitoring' => 'required|exists:monitoring,id_monitoring',
            'file' => 'nullable|file|max:10240',
        ]);

        if ($request->hasFile('file')) {
            Storage::disk('public')->delete($filePelaporan->path_file);
            $path = $request->file('file')->store('file_pelaporan', 'public');
            $validated['path_file'] = $path;
            $validated['nama_file'] = $request->file('file')->getClientOriginalName();
        }

        $validated['uploaded_by'] = auth()->id();
        $validated['uploaded_at'] = now();

        $filePelaporan->update($validated);

        return redirect()->route('file-pelaporan.index')->with('success', 'File berhasil diperbarui.');
    }

    public function destroy(FilePelaporan $filePelaporan)
    {
        Storage::disk('public')->delete($filePelaporan->path_file);
        $filePelaporan->delete();
        return redirect()->route('file-pelaporan.index')->with('success', 'File berhasil dihapus.');
    }
}
