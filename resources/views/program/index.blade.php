@extends('layouts.app')
@section('content')
<h3>Daftar Program</h3>
<a href="{{ route('program.create') }}" class="btn btn-primary mb-3">+ Tambah</a>
<table class="table table-bordered">
    <thead><tr><th>Sasaran Program</th><th>Nama Program</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach ($programs as $item)
        <tr>
            <td>{{ $item->sasaranProgram->nama_sasaran ?? '-' }}</td>
            <td>{{ $item->nama_program }}</td>
            <td>
                <a href="{{ route('program.edit', $item->id_program) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('program.destroy', $item->id_program) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $programs->links() }}
@endsection