@extends('layouts.app')

@section('title')
    KPI {{ $kpi->tahun }} | PSL Track
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
        .accordion-header {
            display: flex;
            align-items: stretch;
        }

        .accordion-header .accordion-button {
            flex: 1;
        }

        .accordion-header .accordion-button::after {
            order: -1;
            margin-left: 0;
            margin-right: 10px;
        }

        .accordion-header-actions {
            display: none;
        }

        .accordion-item:has(> .accordion-collapse.show)>.accordion-header>.accordion-header-actions {
            display: flex;
            align-items: center;
            padding: 0 15px;
            background-color: var(--bs-accordion-active-bg);
        }

        .accordion-header-actions .dropdown-toggle {
            border: 0;
            background: transparent;
            padding: 5px 0;
        }

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
                                KPI PSL Tahun {{ $kpi->tahun }}
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
                                <div class="accordion-item">
                                    {{-- SASARAN PROGRAM --}}
                                    @foreach ($kpi->sasaranProgram as $sasaran)
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
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal"
                                                        data-bs-target="#editSasaranModal{{ $sasaran->id_sasaran }}">
                                                        Edit Sasaran
                                                    </a>
                                                    <form
                                                        action="{{ route('sasaran-program.destroy', $sasaran->id_sasaran) }}"
                                                        method="POST" class="delete-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="dropdown-item"
                                                            onclick="confirmDelete(this, 'sasaran')">
                                                            Hapus Sasaran
                                                        </button>
                                                    </form>
                                                    <div class="dropdown-divider"></div>
                                                    <button type="button" class="btn dropdown-item" data-bs-toggle="modal"
                                                        data-bs-target="#tambahProgramModal"
                                                        data-id-sasaran="{{ $sasaran->id_sasaran }}">
                                                        Tambah Program
                                                    </button>
                                                </div>
                                            </div>
                                        </h2>
                                        {{-- modal edit sasaran --}}
                                        <div class="modal fade" id="editSasaranModal{{ $sasaran->id_sasaran }}"
                                            data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">
                                                            Edit Sasaran Program
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form
                                                        action="{{ route('sasaran-program.update', $sasaran->id_sasaran) }}"
                                                        method="POST">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="id_kpi"
                                                            value="{{ $sasaran->id_kpi }}">
                                                        <div class="modal-body">
                                                            <div class="row mb-2">
                                                                <label for="nama_sasaran"
                                                                    class="col-sm-3 col-form-label">Nama
                                                                    Sasaran</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control"
                                                                        id="nama_sasaran" name="nama_sasaran"
                                                                        value="{{ old('nama_sasaran', $sasaran->nama_sasaran) }}"
                                                                        required />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">
                                                                Batal
                                                            </button>
                                                            <button type="submit" class="btn btn-primary"
                                                                data-bs-dismiss="modal" id="alert-success">
                                                                Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- modal tambah program --}}
                                        <div class="modal fade" id="tambahProgramModal" data-bs-backdrop="static"
                                            data-bs-keyboard="false" tabindex="-1" role="dialog"
                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="staticBackdropLabel">
                                                            Tambah Program
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>
                                                    <form action="{{ route('program.store') }}" method="POST">
                                                        @csrf
                                                        <div class="modal-body">
                                                            <input type="hidden" name="id_sasaran"
                                                                id="id_sasaran_program">
                                                            <div class="row mb-2">
                                                                <label for="nama_program"
                                                                    class="col-sm-3 col-form-label">Nama Program</label>
                                                                <div class="col-sm-9">
                                                                    <input type="text" class="form-control"
                                                                        id="nama_program" name="nama_program"
                                                                        value="{{ old('nama_program') }}" required />
                                                                    @error('nama_program')
                                                                        <div class="invalid-feedback">
                                                                            {{ $message }}
                                                                        </div>
                                                                    @enderror
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-light"
                                                                data-bs-dismiss="modal">
                                                                Batal
                                                            </button>
                                                            <button type="submit" class="btn btn-primary"
                                                                id="alert-success">
                                                                Simpan
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>

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
                                                                                <a class="dropdown-item" href="#"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#editProgramModal{{ $program->id_program }}">
                                                                                    Edit Program
                                                                                </a>
                                                                                <form
                                                                                    action="{{ route('program.destroy', $program->id_program) }}"
                                                                                    method="POST" class="delete-form">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="button"
                                                                                        class="dropdown-item"
                                                                                        onclick="confirmDelete(this, 'program')">
                                                                                        Hapus Program
                                                                                    </button>
                                                                                </form>
                                                                                <div class="dropdown-divider"></div>
                                                                                <a class="dropdown-item"
                                                                                    href="{{ route('indikator-program.create', ['id_program' => $program->id_program]) }}">
                                                                                    Tambah Indikator
                                                                                </a>
                                                                            </div>
                                                                        </div>
                                                                    </h2>
                                                                    {{-- modal edit program --}}
                                                                    <div class="modal fade"
                                                                        id="editProgramModal{{ $program->id_program }}"
                                                                        data-bs-backdrop="static" data-bs-keyboard="false"
                                                                        tabindex="-1" role="dialog"
                                                                        aria-labelledby="staticBackdropLabel"
                                                                        aria-hidden="true">
                                                                        <div class="modal-dialog modal-dialog-centered"
                                                                            role="document">
                                                                            <div class="modal-content">
                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"
                                                                                        id="staticBackdropLabel">
                                                                                        Edit Program
                                                                                    </h5>
                                                                                    <button type="button"
                                                                                        class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"></button>
                                                                                </div>
                                                                                <form
                                                                                    action="{{ route('program.update', $program->id_program) }}"
                                                                                    method="POST">
                                                                                    @csrf @method('PUT')
                                                                                    <input type="hidden"
                                                                                        name="id_sasaran"
                                                                                        value="{{ $program->id_sasaran }}">
                                                                                    <div class="modal-body">
                                                                                        <div class="row mb-2">
                                                                                            <label for="nama_program"
                                                                                                class="col-sm-3 col-form-label">Nama
                                                                                                Program</label>
                                                                                            <div class="col-sm-9">
                                                                                                <input type="text"
                                                                                                    class="form-control"
                                                                                                    id="nama_program"
                                                                                                    name="nama_program"
                                                                                                    value="{{ old('nama_program', $program->nama_program) }}"
                                                                                                    required />
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="modal-footer">
                                                                                        <button type="button"
                                                                                            class="btn btn-light"
                                                                                            data-bs-dismiss="modal">
                                                                                            Batal
                                                                                        </button>
                                                                                        <button type="submit"
                                                                                            class="btn btn-primary"
                                                                                            data-bs-dismiss="modal"
                                                                                            id="alert-success">
                                                                                            Simpan
                                                                                        </button>
                                                                                    </div>
                                                                                </form>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    {{-- end modal --}}
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
                                                                                                <th data-priority="1">
                                                                                                    Target
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
                                                                                                    <td>
                                                                                                        @foreach ($indikator->picIndikator ?? [] as $pic)
                                                                                                            @php
                                                                                                                $namaPic = explode(
                                                                                                                    ' ',
                                                                                                                    trim(
                                                                                                                        $pic
                                                                                                                            ->user
                                                                                                                            ->nama,
                                                                                                                    ),
                                                                                                                );
                                                                                                                $namaSingkat = implode(
                                                                                                                    ' ',
                                                                                                                    array_slice(
                                                                                                                        $namaPic,
                                                                                                                        0,
                                                                                                                        2,
                                                                                                                    ),
                                                                                                                );
                                                                                                            @endphp

                                                                                                            <span
                                                                                                                class="badge bg-primary">
                                                                                                                {{ $namaSingkat }}
                                                                                                            </span>
                                                                                                        @endforeach
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
                                                                                                                    href="{{ route('indikator-program.edit', $indikator->id_indikator) }}">Edit
                                                                                                                    Indikator</a>
                                                                                                                <form
                                                                                                                    action="{{ route('indikator-program.destroy', $indikator->id_indikator) }}"
                                                                                                                    method="POST"
                                                                                                                    class="delete-form">
                                                                                                                    @csrf
                                                                                                                    @method('DELETE')
                                                                                                                    <button
                                                                                                                        type="button"
                                                                                                                        class="dropdown-item"
                                                                                                                        onclick="confirmDelete(this, 'indikator')">
                                                                                                                        Hapus
                                                                                                                        Indikator
                                                                                                                    </button>
                                                                                                                </form>
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
                                            {{-- modal edit program --}}
                                            <div class="modal fade" id="editSasaranModal{{ $sasaran->id_sasaran }}"
                                                data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                                                role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="staticBackdropLabel">
                                                                Edit Sasaran Program
                                                            </h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form
                                                            action="{{ route('sasaran-program.update', $sasaran->id_sasaran) }}"
                                                            method="POST">
                                                            @csrf @method('PUT')
                                                            <input type="hidden" name="id_kpi"
                                                                value="{{ $sasaran->id_kpi }}">
                                                            <div class="modal-body">
                                                                <div class="row mb-2">
                                                                    <label for="nama_sasaran"
                                                                        class="col-sm-3 col-form-label">Nama
                                                                        Sasaran</label>
                                                                    <div class="col-sm-9">
                                                                        <input type="text" class="form-control"
                                                                            id="nama_sasaran" name="nama_sasaran"
                                                                            value="{{ old('nama_sasaran', $sasaran->nama_sasaran) }}"
                                                                            required />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-light"
                                                                    data-bs-dismiss="modal">
                                                                    Batal
                                                                </button>
                                                                <button type="submit" class="btn btn-primary"
                                                                    data-bs-dismiss="modal" id="alert-success">
                                                                    Simpan
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- end modal --}}
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

    {{-- SweetAlert Hapus --}}
    <script>
        function confirmDelete(button, type) {

            let title = '';
            let text = '';

            if (type === 'sasaran') {
                title = 'Hapus Sasaran?';
                text =
                    'Menghapus sasaran juga dapat menghapus data program dan indikator di dalamnya. Data yang telah dihapus tidak dapat dikembalikan.';
            }

            if (type === 'program') {
                title = 'Hapus Program?';
                text =
                    'Menghapus program juga dapat menghapus indikator di dalamnya. Data yang telah dihapus tidak dapat dikembalikan.';
            }

            if (type === 'indikator') {
                title = 'Hapus Indikator?';
                text = 'Data indikator yang dihapus tidak dapat dikembalikan.';
            }

            Swal.fire({
                title: title,
                text: text,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#2ab57d',
                cancelButtonColor: '#fd625e',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {

                if (result.isConfirmed) {
                    button.closest('form').submit();
                }

            });
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const tambahProgramModal = document.getElementById('tambahProgramModal');

            tambahProgramModal.addEventListener('show.bs.modal', function(event) {

                const button = event.relatedTarget;

                const idSasaran = button.getAttribute('data-id-sasaran');

                document.getElementById('id_sasaran_program').value = idSasaran;
            });

        });
    </script>
@endsection
