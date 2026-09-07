@extends('layouts.app')
@section('content')
<h3>Tambah PIC Indikator</h3>
<form action="{{ route('pic-indikator.store') }}" method="POST">
    @csrf
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
        <label>User (PIC)</label>
        <select name="id_user" class="form-control" required>
            <option value="">-- Pilih User --</option>
            @foreach ($users as $u)
                <option value="{{ $u->id_user }}">{{ $u->nama }}</option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('pic-indikator.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection