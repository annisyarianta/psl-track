@extends('layouts.app')

@section('title')
    Export Data | PSL Track
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
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h4 class="mb-sm-0">
                                Ekspor Data Laporan
                            </h4>
                        </div>
                        <div class="row justify-content-center">
                            <div class="col-lg-6">
                                <div class="card shadow-sm">
                                    <div class="card-body">
                                        <form method="GET">
                                            <div class="mb-4">
                                                <label for="filterTahun" class="form-label">Tahun</label>
                                                <select name="id_tahun" id="filterTahun" class="form-select" required>
                                                    <option value="">Pilih tahun</option>
                                                    @foreach ($daftarTahun as $tahun)
                                                        <option value="{{ $tahun->id_tahun }}">{{ $tahun->tahun }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label for="filterUnit" class="form-label">Unit</label>
                                                <select name="id_unit" id="filterUnit" class="form-select">
                                                    <option value="">Semua unit</option>
                                                    @foreach ($daftarUnit as $unit)
                                                        <option value="{{ $unit->id_unit }}">{{ $unit->nama_unit }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <button type="submit" formaction="{{ route('export-laporan.pdf') }}"
                                                    class="btn btn-danger waves-effect btn-label waves-light">
                                                    <i class="bx bxs-file-pdf label-icon"></i> Generate PDF
                                                </button>
                                                <button type="submit" formaction="{{ route('export-laporan.excel') }}"
                                                    class="btn btn-success waves-effect btn-label waves-light ms-3">
                                                    <i class="bx bx-table label-icon"></i> Generate Excel
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
