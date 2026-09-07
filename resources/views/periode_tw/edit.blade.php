@extends('layouts.app')
@section('content')
<h3>Edit Periode TW</h3>
<form action="{{ route('periode-tw.update', $periodeTw->id_periode_tw) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>KPI</label>
        <select name="id_kpi" class="form-control" required>
            @foreach ($kpis as $kpi)
                <option value="{{ $kpi->id_kpi }}" {{ $periodeTw->id_kpi == $kpi->id_kpi ? 'selected' : '' }}>
                    {{ $kpi->judul_kpi }} ({{ $kpi->tahun }})
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Triwulan</label>
        <select name="triwulan" class="form-control" required>
            @for ($i = 1; $i <= 4; $i++)
                <option value="{{ $i }}" {{ $periodeTw->triwulan == $i ? 'selected' : '' }}>TW {{ $i }}</option>
            @endfor
        </select>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('periode-tw.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection