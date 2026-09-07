@extends('layouts.app')
@section('content')
<h3>Daftar Indikator Program</h3>
<a href="{{ route('indikator-program.create') }}" class="btn btn-primary mb-3">+ Tambah</a>
<table class="table table-bordered">
    <thead><tr><th>Program</th><th>Nama Indikator</th><th>Target</th><th>Aspek</th><th>Due Date</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach ($indikatorPrograms as $item)
        <tr>
            <td>{{ $item->program->nama_program ?? '-' }}</td>
            <td>{{ $item->nama_indikator }}</td>
            <td>{{ $item->target }}</td>
            <td>{{ $item->aspek }}</td>
            <td>{{ $item->due_date }}</td>
            <td>
                <a href="{{ route('indikator-program.edit', $item->id_indikator) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('indikator-program.destroy', $item->id_indikator) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $indikatorPrograms->links() }}
@endsection