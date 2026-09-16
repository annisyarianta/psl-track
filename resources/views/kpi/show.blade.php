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

    <style>
        /* Header accordion */
        .accordion-header {
            display: flex;
            align-items: stretch;
        }

        /* Tombol accordion tetap mengambil sisa ruang */
        .accordion-header .accordion-button {
            flex: 1;
        }

        /* Panah di sebelah kiri */
        .accordion-header .accordion-button::after {
            order: -1;
            margin-left: 0;
            margin-right: 10px;
        }

        /* Titik tiga: default tersembunyi */
        .accordion-header-actions {
            display: none;
        }

        /* Saat accordion TERBUKA, titik tiga muncul
                                                   dan background mengikuti warna accordion-button */
        .accordion-item:has(> .accordion-collapse.show)>.accordion-header>.accordion-header-actions {
            display: flex;
            align-items: center;
            padding: 0 15px;
            background-color: var(--bs-accordion-active-bg);
        }

        /* Tombol titik tiga */
        .accordion-header-actions .dropdown-toggle {
            border: 0;
            background: transparent;
            padding: 5px 0;
        }

        /* Hilangkan tanda panah dropdown */
        .accordion-header-actions .dropdown-toggle::after {
            display: none;
        }
    </style>

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
                        <div class="card-body">
                            <div class="accordion" id="accordionExample">
                                @foreach ($kpi->sasaranProgram as $sasaran)
                                    {{-- SASARAN PROGRAM --}}
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button fw-bold" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                {{ $sasaran->nama_sasaran }}
                                            </button>
                                            <div class="accordion-header-actions dropdown">
                                                <button type="button" class="btn waves-effect waves-light dropdown-toggle"
                                                    data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="fas fa-ellipsis-v"></i>
                                                </button>
                                                <div class="dropdown-menu dropdown-menu-end dropdownmenu-primary">
                                                    <a class="dropdown-item" href="#">
                                                        Edit Sasaran
                                                    </a>
                                                    <a class="dropdown-item" href="#">
                                                        Hapus Sasaran
                                                    </a>
                                                    <div class="dropdown-divider"></div>
                                                    <a class="dropdown-item" href="#">
                                                        Tambah Program
                                                    </a>
                                                </div>
                                            </div>
                                        </h2>
                                        {{-- PROGRAM --}}
                                        @foreach ($sasaran->program as $program)
                                            <div id="collapseOne" class="accordion-collapse collapse show"
                                                aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="text-muted">
                                                        <div class="card-body">
                                                            <div class="accordion" id="accordionPanelsStayOpenExample">
                                                                <div class="accordion-item">
                                                                    <h2 class="accordion-header"
                                                                        id="panelsStayOpen-headingOne">
                                                                        <button class="accordion-button" type="button"
                                                                            data-bs-toggle="collapse"
                                                                            data-bs-target="#panelsStayOpen-collapseOne"
                                                                            aria-expanded="true"
                                                                            aria-controls="panelsStayOpen-collapseOne">
                                                                            {{ $program->nama_program }} </button>
                                                                        <div class="accordion-header-actions dropdown">
                                                                            <button type="button"
                                                                                class="btn waves-effect waves-light dropdown-toggle"
                                                                                data-bs-toggle="dropdown"
                                                                                aria-expanded="false">
                                                                                <i class="fas fa-ellipsis-v"></i>
                                                                            </button>
                                                                            <div
                                                                                class="dropdown-menu dropdown-menu-end dropdownmenu-primary">
                                                                                <a class="dropdown-item" href="#">
                                                                                    Edit Program
                                                                                </a>
                                                                                <a class="dropdown-item" href="#">
                                                                                    Hapus Program
                                                                                </a>
                                                                                <div class="dropdown-divider"></div>
                                                                                <a class="dropdown-item" href="#">
                                                                                    Tambah Indikator
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </h2>
                                                                    {{-- INDIKATOR PROGRAM --}}
                                                                    <div id="panelsStayOpen-collapseOne"
                                                                        class="accordion-collapse collapse show"
                                                                        aria-labelledby="panelsStayOpen-headingOne">
                                                                        <div class="accordion-body">
                                                                            <div class="table-rep-plugin">
                                                                                <div class="table-responsive mb-0"
                                                                                    data-pattern="priority-columns">
                                                                                    <table id="tech-companies-1"
                                                                                        class="table table-striped">
                                                                                        <thead>
                                                                                            <tr>
                                                                                                <th data-priority="1">No.
                                                                                                </th>
                                                                                                <th data-priority="3">
                                                                                                    Indikator
                                                                                                    Program
                                                                                                </th>
                                                                                                <th data-priority="1">Target
                                                                                                </th>
                                                                                                <th data-priority="3">
                                                                                                    Periode
                                                                                                    Pengukuran
                                                                                                </th>
                                                                                                <th data-priority="3">PIC
                                                                                                </th>
                                                                                                <th data-priority="6">Aksi
                                                                                                </th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            @foreach ($program->indikatorProgram as $indikator)
                                                                                                <tr>
                                                                                                    <th>{{ $loop->iteration }}
                                                                                                    </th>
                                                                                                    <td>{{ $indikator->nama_indikator }}
                                                                                                    </td>
                                                                                                    <td>{{ $indikator->target }}
                                                                                                    </td>
                                                                                                    <td>{{ $indikator->periode_pengukuran }}
                                                                                                    </td>
                                                                                                    <td>{{ $indikator->pic }}
                                                                                                    </td>
                                                                                                    <td>
                                                                                                        <div
                                                                                                            class="btn-group dropend">
                                                                                                            <button
                                                                                                                type="button"
                                                                                                                class="btn waves-effect waves-light dropdown-toggle"
                                                                                                                data-bs-toggle="dropdown"
                                                                                                                aria-expanded="false">
                                                                                                                <i
                                                                                                                    class="fas fa-ellipsis-v"></i>
                                                                                                            </button>
                                                                                                            <div
                                                                                                                class="dropdown-menu dropdownmenu-primary">
                                                                                                                <a class="dropdown-item"
                                                                                                                    href="#">Edit
                                                                                                                    Indikator</a>
                                                                                                                <a class="dropdown-item"
                                                                                                                    href="#">Hapus
                                                                                                                    Indikator</a>
                                                                                                                <div
                                                                                                                    class="dropdown-divider">
                                                                                                                </div>
                                                                                                                <a class="dropdown-item"
                                                                                                                    href="#">Lihat
                                                                                                                    Monitoring</a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </td>
                                                                                                </tr>
                                                                                            @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div> <!-- end card body -->
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endforeach
                            </div><!-- end accordion -->
                        </div><!-- end card-body -->
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
