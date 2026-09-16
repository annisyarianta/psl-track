@extends('layouts.app')

@section('title')
    Detail KPI | PSL Track
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
                                Detail KPI
                            </h3>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('kpi.index') }}">KPI Tracker</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        <a href="{{ route('kpi.show', $kpi->id_kpi) }}">Detail KPI</a>
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
                                onclick="window.location.href='{{ route('kpi.struktur.create', $kpi->id_kpi) }}'"><i
                                    class="bx bx-plus label-icon"></i>Tambah Sasaran</button>
                        </div>
                        <div class="row">
                            @foreach ($kpi->sasaranProgram as $sasaran)
                                <div class="card mb-3">
                                    <div class="card-header">
                                        <h5 class="mb-0">
                                            {{ $sasaran->nama_sasaran }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        {{-- PROGRAM --}}
                                        @foreach ($sasaran->program as $program)
                                            <div class="card border mb-3">
                                                <div class="card-header">
                                                    <strong>
                                                        {{ $program->nama_program }}
                                                    </strong>
                                                </div>
                                                <div class="card-body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>No.</th>
                                                                    <th>Indikator Program</th>
                                                                    <th>Target</th>
                                                                    <th>Periode Pengukuran</th>
                                                                    <th>PIC</th>
                                                                    <th>Aksi</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($program->indikatorProgram as $indikator)
                                                                    <tr>
                                                                        <td>
                                                                            {{ $loop->iteration }}
                                                                        </td>

                                                                        <td>
                                                                            {{ $indikator->nama_indikator }}
                                                                        </td>

                                                                        <td>
                                                                            {{ $indikator->target }}
                                                                        </td>

                                                                        <td>
                                                                            {{ $indikator->periode_pengukuran }}
                                                                        </td>

                                                                        <td>
                                                                            {{ $indikator->pic }}
                                                                        </td>

                                                                        <td>
                                                                            ...
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
