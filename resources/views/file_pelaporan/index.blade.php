@extends('layouts.app')
@section('content')
<h3>Daftar File Pelaporan</h3>
<a href="{{ route('file-pelaporan.create') }}" class="btn btn-primary mb-3">+ Tambah</a>
<table class="table table-bordered">
    <thead><tr><th>Monitoring</th><th>Nama File</th><th>Diunggah</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach ($filePelaporans as $item)
        <tr>
            <td>{{ $item->monitoring->id_monitoring ?? '-' }}</td>
            <td><a href="{{ asset('storage/' . $item->path_file) }}" target="_blank">{{ $item->nama_file }}</a></td>
            <td>{{ $item->uploaded_at }}</td>
            <td>
                <a href="{{ route('file-pelaporan.edit', $item->id_file) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('file-pelaporan.destroy', $item->id_file) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $filePelaporans->links() }}
@endsection