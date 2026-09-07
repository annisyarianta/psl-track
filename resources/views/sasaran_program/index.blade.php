@extends('layouts.app')
@section('content')
<h3>Daftar Sasaran Program</h3>
<a href="{{ route('sasaran-program.create') }}" class="btn btn-primary mb-3">+ Tambah</a>
<table class="table table-bordered">
    <thead><tr><th>KPI</th><th>Nama Sasaran</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach ($sasaranPrograms as $item)
        <tr>
            <td>{{ $item->kpi->judul_kpi ?? '-' }}</td>
            <td>{{ $item->nama_sasaran }}</td>
            <td>
                <a href="{{ route('sasaran-program.edit', $item->id_sasaran) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('sasaran-program.destroy', $item->id_sasaran) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $sasaranPrograms->links() }}
@endsection