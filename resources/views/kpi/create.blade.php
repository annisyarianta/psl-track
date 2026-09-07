@extends('layouts.app')
@section('content')
<h3>Tambah KPI</h3>
<form action="{{ route('kpi.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Tahun</label>
        <input type="number" name="tahun" class="form-control" value="{{ old('tahun') }}" required>
    </div>
    <div class="mb-3">
        <label>Judul KPI</label>
        <input type="text" name="judul_kpi" class="form-control" value="{{ old('judul_kpi') }}" required>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('kpi.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection