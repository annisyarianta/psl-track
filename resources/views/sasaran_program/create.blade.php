@extends('layouts.app')
@section('content')
<h3>Tambah Sasaran Program</h3>
<form action="{{ route('sasaran-program.store') }}" method="POST">
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
        <label>Nama Sasaran</label>
        <input type="text" name="nama_sasaran" class="form-control" value="{{ old('nama_sasaran') }}" required>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('sasaran-program.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection