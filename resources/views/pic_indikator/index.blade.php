@extends('layouts.app')
@section('content')
<h3>Daftar PIC Indikator</h3>
<a href="{{ route('pic-indikator.create') }}" class="btn btn-primary mb-3">+ Tambah</a>
<table class="table table-bordered">
    <thead><tr><th>Indikator</th><th>User (PIC)</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach ($picIndikators as $item)
        <tr>
            <td>{{ $item->indikatorProgram->nama_indikator ?? '-' }}</td>
            <td>{{ $item->user->nama ?? '-' }}</td>
            <td>
                <a href="{{ route('pic-indikator.edit', $item->id_pic_indikator) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('pic-indikator.destroy', $item->id_pic_indikator) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $picIndikators->links() }}
@endsection