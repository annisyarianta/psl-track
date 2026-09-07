@extends('layouts.app')
@section('content')
<h3>Tambah Periode TW</h3>
<form action="{{ route('periode-tw.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>KPI</label>
        <select name="id_kpi" class="form-control" required>
            <option value="">-- Pilih KPI --</option>
            @foreach ($kpis as $kpi)
                <option value="{{ $kpi->id_kpi }}">{{ $kpi->judul_kpi }} ({{ $kpi->tahun }})</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Triwulan</label>
        <select name="triwulan" class="form-control" required>
            <option value="1">TW 1</option>
            <option value="2">TW 2</option>
            <option value="3">TW 3</option>
            <option value="4">TW 4</option>
        </select>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('periode-tw.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection