@extends('layouts.app')
@section('content')
<h3>Edit Indikator Program</h3>
<form action="{{ route('indikator-program.update', $indikatorProgram->id_indikator) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Program</label>
        <select name="id_program" class="form-control" required>
            @foreach ($programs as $p)
                <option value="{{ $p->id_program }}" {{ $indikatorProgram->id_program == $p->id_program ? 'selected' : '' }}>
                    {{ $p->nama_program }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nama Indikator</label>
        <input type="text" name="nama_indikator" class="form-control" value="{{ old('nama_indikator', $indikatorProgram->nama_indikator) }}" required>
    </div>
    <div class="mb-3">
        <label>Target</label>
        <input type="text" name="target" class="form-control" value="{{ old('target', $indikatorProgram->target) }}">
    </div>
    <div class="mb-3">
        <label>Aspek</label>
        <select name="aspek" class="form-control">
            <option value="">-- Pilih Aspek --</option>
            @foreach ($aspekOptions as $opt)
                <option value="{{ $opt }}" {{ $indikatorProgram->aspek == $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Periode Pengukuran</label>
        <input type="text" name="periode_pengukuran" class="form-control" value="{{ old('periode_pengukuran', $indikatorProgram->periode_pengukuran) }}">
    </div>
    <div class="mb-3">
        <label>Upaya</label>
        <input type="text" name="upaya" class="form-control" value="{{ old('upaya', $indikatorProgram->upaya) }}">
    </div>
    <div class="mb-3">
        <label>Due Date</label>
        <input type="datetime-local" name="due_date" class="form-control" value="{{ old('due_date', $indikatorProgram->due_date) }}">
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('indikator-program.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection