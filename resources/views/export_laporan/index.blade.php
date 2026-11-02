@extends('layouts.app')

@section('title')
    Export Laporan | PSL Track
@endsection

@section('content')
    @if (session('success'))
        <script>
            alertify.success(@json(session('success')));
        </script>
    @endif

    @if (session('error'))
        <script>
            alertify.error(@json(session('error')));
        </script>
    @endif
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Export Laporan KPI</h5>

                                <form method="GET">
                                    <div class="mb-3">
                                        <label for="filterTahun" class="form-label">Tahun</label>
                                        <select name="id_tahun" id="filterTahun" class="form-select" required>
                                            <option value="">Pilih tahun</option>
                                            @foreach ($daftarTahun as $tahun)
                                                <option value="{{ $tahun->id_tahun }}">{{ $tahun->tahun }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label for="filterUnit" class="form-label">Unit</label>
                                        <select name="id_unit" id="filterUnit" class="form-select">
                                            <option value="">Semua unit</option>
                                            @foreach ($daftarUnit as $unit)
                                                <option value="{{ $unit->id_unit }}">{{ $unit->nama_unit }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit" formaction="{{ route('export-laporan.pdf') }}"
                                        class="btn btn-danger">
                                        <i class="mdi mdi-file-pdf-box"></i> Generate PDF
                                    </button>
                                    <button type="submit" formaction="{{ route('export-laporan.excel') }}"
                                        class="btn btn-success">
                                        <i class="mdi mdi-file-excel-box"></i> Generate Excel
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
