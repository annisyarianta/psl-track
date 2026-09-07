@extends('layouts.app')
@section('content')
<h3>Tambah Monitoring</h3>
<form action="{{ route('monitoring.store') }}" method="POST">
    @csrf
    <div class="mb-3">
        <label>Periode TW</label>
        <select name="id_periode_tw" class="form-control" required>
            <option value="">-- Pilih Periode TW --</option>
            @foreach ($periodeTws as $pt)
                <option value="{{ $pt->id_periode_tw }}">TW {{ $pt->triwulan }} - {{ $pt->kpi->judul_kpi ?? '' }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Indikator</label>
        <select name="id_indikator" class="form-control" required>
            <option value="">-- Pilih Indikator --</option>
            @foreach ($indikatorPrograms as $ip)
                <option value="{{ $ip->id_indikator }}">{{ $ip->nama_indikator }}</option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Capaian</label>
        <input type="text" name="capaian" class="form-control" value="{{ old('capaian') }}">
    </div>
    <div class="mb-3">
        <label>Keterangan</label>
        <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan') }}">
    </div>
    <div class="mb-3">
        <label>Identifikasi</label>
        <input type="text" name="identifikasi" class="form-control" value="{{ old('identifikasi') }}">
    </div>
    <div class="mb-3">
        <label>Status</label>
        <select name="status" class="form-control" required>
            @foreach ($statusOptions as $opt)
                <option value="{{ $opt }}">{{ $opt }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('monitoring.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection