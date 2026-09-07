@extends('layouts.app')
@section('content')
<h3>Edit Sasaran Program</h3>
<form action="{{ route('sasaran-program.update', $sasaranProgram->id_sasaran) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>KPI</label>
        <select name="id_kpi" class="form-control" required>
            @foreach ($kpis as $kpi)
                <option value="{{ $kpi->id_kpi }}" {{ $sasaranProgram->id_kpi == $kpi->id_kpi ? 'selected' : '' }}>
                    {{ $kpi->judul_kpi }} ({{ $kpi->tahun }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nama Sasaran</label>
        <input type="text" name="nama_sasaran" class="form-control" value="{{ old('nama_sasaran', $sasaranProgram->nama_sasaran) }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('sasaran-program.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection