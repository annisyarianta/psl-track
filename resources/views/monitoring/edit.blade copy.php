@extends('layouts.app')
@section('content')
<h3>Edit Monitoring</h3>
<form action="{{ route('monitoring.update', $monitoring->id_monitoring) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Periode TW</label>
        <select name="id_periode_tw" class="form-control" required>
            @foreach ($periodeTws as $pt)
                <option value="{{ $pt->id_periode_tw }}" {{ $monitoring->id_periode_tw == $pt->id_periode_tw ? 'selected' : '' }}>
                    TW {{ $pt->triwulan }} - {{ $pt->kpi->judul_kpi ?? '' }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Indikator</label>
        <select name="id_indikator" class="form-control" required>
            @foreach ($indikatorPrograms as $ip)
                <option value="{{ $ip->id_indikator }}" {{ $monitoring->id_indikator == $ip->id_indikator ? 'selected' : '' }}>
                    {{ $ip->nama_indikator }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Capaian</label>
        <input type="text" name="capaian" class="form-control" value="{{ old('capaian', $monitoring->capaian) }}">
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan', $monitoring->keterangan) }}">
    </div>
    <div class="mb-3">
        <label>Identifikasi</label>
        <input type="text" name="identifikasi" class="form-control" value="{{ old('identifikasi', $monitoring->identifikasi) }}">
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            @foreach ($statusOptions as $opt)
                <option value="{{ $opt }}" {{ $monitoring->status == $opt ? 'selected' : '' }}>{{ $opt }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('monitoring.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection