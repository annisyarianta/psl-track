@extends('layouts.app')
@section('content')
<h3>Edit KPI</h3>
<form action="{{ route('kpi.update', $kpi->id_kpi) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Tahun</label>
        <input type="number" name="tahun" class="form-control" value="{{ old('tahun', $kpi->tahun) }}" required>
    </div>
    <div class="mb-3">
        <label>Judul KPI</label>
        <input type="text" name="judul_kpi" class="form-control" value="{{ old('judul_kpi', $kpi->judul_kpi) }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('kpi.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection