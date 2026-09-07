@extends('layouts.app')
@section('content')
<h3>Unggah File Pelaporan</h3>
<form action="{{ route('file-pelaporan.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="mb-3">
        <label>Monitoring</label>
        <select name="id_monitoring" class="form-control" required>
            <option value="">-- Pilih Monitoring --</option>
            @foreach ($monitorings as $m)
                <option value="{{ $m->id_monitoring }}">#{{ $m->id_monitoring }} - {{ $m->indikatorProgram->nama_indikator ?? '' }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>File</label>
        <input type="file" name="file" class="form-control" required>
    </div>
    <button class="btn btn-primary">Unggah</button>
    <a href="{{ route('file-pelaporan.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection