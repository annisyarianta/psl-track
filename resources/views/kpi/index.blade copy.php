@extends('layouts.app')
@section('content')
<h3>Daftar KPI</h3>
<a href="{{ route('kpi.create') }}" class="btn btn-primary mb-3">+ Tambah KPI</a>
<table class="table table-bordered">
    <thead>
        <tr>
            <th>Tahun</th>
            <th>Judul KPI</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($kpis as $kpi)
        <tr>
            <td>{{ $kpi->tahun }}</td>
            <td>{{ $kpi->judul_kpi }}</td>
            <td>
                <a href="{{ route('kpi.edit', $kpi->id_kpi) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('kpi.destroy', $kpi->id_kpi) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
{{ $kpis->links() }}
@endsection