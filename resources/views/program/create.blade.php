@extends('layouts.app')
@section('content')
<h3>Tambah Program</h3>
<form action="{{ route('program.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Sasaran Program</label>
        <select name="id_sasaran" class="form-control" required>
            <option value="">-- Pilih Sasaran Program --</option>
            @foreach ($sasaranPrograms as $sp)
                <option value="{{ $sp->id_sasaran }}">{{ $sp->nama_sasaran }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nama Program</label>
        <input type="text" name="nama_program" class="form-control" value="{{ old('nama_program') }}" required>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('program.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection