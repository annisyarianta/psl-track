@extends('layouts.app')
@section('content')
<h3>Daftar Monitoring</h3>
<a href="{{ route('monitoring.create') }}" class="btn btn-primary mb-3">+ Tambah</a>
<table class="table table-bordered">
    <thead><tr><th>Periode TW</th><th>Indikator</th><th>Capaian</th><th>Status</th><th>Aksi</th></tr></thead>
    <tbody>
    @foreach ($monitorings as $item)
        <tr>
            <td>TW {{ $item->periodeTw->triwulan ?? '-' }}</td>
            <td>{{ $item->indikatorProgram->nama_indikator ?? '-' }}</td>
            <td>{{ $item->capaian }}</td>
            <td>{{ $item->status }}</td>
            <td>
                <a href="{{ route('monitoring.edit', $item->id_monitoring) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('monitoring.destroy', $item->id_monitoring) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
{{ $monitorings->links() }}
@endsection