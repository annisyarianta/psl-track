@extends('layouts.app')
@section('content')
<h3>Edit File Pelaporan</h3>
<form action="{{ route('file-pelaporan.update', $filePelaporan->id_file) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Monitoring</label>
        <select name="id_monitoring" class="form-control" required>
            @foreach ($monitorings as $m)
                <option value="{{ $m->id_monitoring }}" {{ $filePelaporan->id_monitoring == $m->id_monitoring ? 'selected' : '' }}>
                    #{{ $m->id_monitoring }} - {{ $m->indikatorProgram->nama_indikator ?? '' }}
                </option>
            @endforeach
        </select>
    </div>
    <p>File saat ini: <a href="{{ asset('storage/' . $filePelaporan->path_file) }}" target="_blank">{{ $filePelaporan->nama_file }}</a></p>
    <div class="mb-3">
        <label>Ganti File (opsional)</label>
        <input type="file" name="file" class="form-control">
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('file-pelaporan.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection