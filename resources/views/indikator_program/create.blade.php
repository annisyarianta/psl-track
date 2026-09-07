@extends('layouts.app')
@section('content')
<h3>Tambah Indikator Program</h3>
<form action="{{ route('indikator-program.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Program</label>
        <select name="id_program" class="form-control" required>
            <option value="">-- Pilih Program --</option>
            @foreach ($programs as $p)
                <option value="{{ $p->id_program }}">{{ $p->nama_program }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nama Indikator</label>
        <input type="text" name="nama_indikator" class="form-control" value="{{ old('nama_indikator') }}" required>
    </div>
    <div class="mb-3">
        <label>Target</label>
        <input type="text" name="target" class="form-control" value="{{ old('target') }}">
    </div>
    <div class="mb-3">
        <label>Aspek</label>
        <select name="aspek" class="form-control">
            <option value="">-- Pilih Aspek --</option>
            @foreach ($aspekOptions as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Periode Pengukuran</label>
        <input type="text" name="periode_pengukuran" class="form-control" value="{{ old('periode_pengukuran') }}">
    </div>
    <div class="mb-3">
        <label>Upaya</label>
        <input type="text" name="upaya" class="form-control" value="{{ old('upaya') }}">
    </div>
    <div class="mb-3">
        <label>Due Date</label>
        <input type="datetime-local" name="due_date" class="form-control" value="{{ old('due_date') }}">
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('indikator-program.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection