@extends('layouts.app')
@section('content')
<h3>Edit PIC Indikator</h3>
<form action="{{ route('pic-indikator.update', $picIndikator->id_pic_indikator) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Indikator</label>
        <select name="id_indikator" class="form-control" required>
            @foreach ($indikatorPrograms as $ip)
                <option value="{{ $ip->id_indikator }}" {{ $picIndikator->id_indikator == $ip->id_indikator ? 'selected' : '' }}>
                    {{ $ip->nama_indikator }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>User (PIC)</label>
        <select name="id_user" class="form-control" required>
            @foreach ($users as $u)
                <option value="{{ $u->id_user }}" {{ $picIndikator->id_user == $u->id_user ? 'selected' : '' }}>
                    {{ $u->nama }}
                </option>
            @endforeach
        </select>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('pic-indikator.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection