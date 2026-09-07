@extends('layouts.app')
@section('content')
<h3>Daftar Periode TW</h3>
<a href="{{ route('periode-tw.create') }}" class="btn btn-primary mb-3">+ Tambah</a>
<table class="table table-bordered">
    <thead><tr><th>KPI</th><th>Triwulan</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach ($periodeTws as $item)
        <tr>
            <td>{{ $item->kpi->judul_kpi ?? '-' }}</td>
            <td>TW {{ $item->triwulan }}</td>
            <td>
                <a href="{{ route('periode-tw.edit', $item->id_periode_tw) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('periode-tw.destroy', $item->id_periode_tw) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $periodeTws->links() }}
@endsection