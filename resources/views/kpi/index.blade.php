@extends('layouts.app')

@section('title')
    KPI | PSL Track
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
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">
                                PSL KPI Tracker
                            </h3>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">Tracker</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        <a href="{{ route('kpi.index') }}">KPI Tracker</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="card-header d-flex justify-content-end mb-4">
                            <button type="button" class="btn btn-primary waves-effect btn-label waves-light"
                                data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                                <i class="bx bx-plus label-icon"></i>Tambah KPI
                            </button>
                        </div>
                        {{-- modal tambah data --}}
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false"
                            tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">
                                            Tambah KPI Baru
                                        </h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form action="{{ route('kpi.store') }}" method="POST">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row mb-4">
                                                <label for="tahun" class="col-sm-3 col-form-label">Tahun</label>
                                                <div class="col-sm-9">
                                                    <input type="number" class="form-control" id="tahun" name="tahun"
                                                        value="{{ old('tahun') }}" required />
                                                </div>
                                            </div>
                                            <div class="row mb-2">
                                                <label for="judul_kpi" class="col-sm-3 col-form-label">Judul KPI</label>
                                                <div class="col-sm-9">
                                                    <input type="text" class="form-control" id="judul_kpi" name="judul_kpi"
                                                        value="{{ old('judul_kpi') }}" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-primary" data-bs-dismiss="modal"
                                                id="alert-success">
                                                Simpan
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        {{-- end modal --}}
                        <div class="row">
                            @foreach ($kpis as $kpi)
                                <div class="col-sm-6 col-lg-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <h5 class="card-title">{{ $kpi->tahun }}</h5>
                                            <p class="card-text">{{ $kpi->judul_kpi }}
                                            </p>
                                            <div class="d-flex justify-content-between align-items-center">
                                                <p class="card-text mb-0">
                                                    <small class="text-muted">Last updated by Dilla Anggraeni, S.T.,
                                                        M.T</small>
                                                </p>

                                                <a href="{{ route('kpi.show', $kpi->id_kpi) }}" class="card-link">
                                                    Detail >>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ $kpis->links() }}
@endsection
