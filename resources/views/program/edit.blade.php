@extends('layouts.app')
@section('content')
<h3>Edit Program</h3>
<form action="{{ route('program.update', $program->id_program) }}" method="POST">
    @csrf @method('PUT')
    <div class="mb-3">
        <label>Sasaran Program</label>
        <select name="id_sasaran" class="form-control" required>
            @foreach ($sasaranPrograms as $sp)
                <option value="{{ $sp->id_sasaran }}" {{ $program->id_sasaran == $sp->id_sasaran ? 'selected' : '' }}>
                    {{ $sp->nama_sasaran }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="mb-3">
        <label>Nama Program</label>
        <input type="text" name="nama_program" class="form-control" value="{{ old('nama_program', $program->nama_program) }}" required>
    </div>
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('program.index') }}" class="btn btn-secondary">Batal</a>
</form>
@endsection