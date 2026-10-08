@extends('layouts.app')

@section('title')
    Master Unit | PSL Track
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
                                Data Unit
                            </h3>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">Master Data</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        <a href="{{ route('unit.index') }}">Master Unit</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-end">
                                <button type="button" class="btn btn-primary waves-effect btn-label waves-light"
                                    data-bs-toggle="modal" data-bs-target="#tambahUnitModal"><i
                                        class="bx bx-plus label-icon"></i>Tambah Unit</button>
                            </div>
                            {{-- modal tambah unit --}}
                            <div class="modal fade" id="tambahUnitModal" data-bs-backdrop="static" data-bs-keyboard="false"
                                tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="staticBackdropLabel">
                                                Tambah Unit
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('unit.store') }}" method="POST">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="row mb-2">
                                                    <label for="nama_unit" class="col-sm-3 col-form-label">Nama
                                                        Unit <span class="text-danger">*</span></label>
                                                    <div class="col-sm-9">
                                                        <input type="text" class="form-control" id="nama_unit"
                                                            name="nama_unit" value="{{ old('nama_unit') }}" required />
                                                        @error('nama_unit')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                                    Batal
                                                </button>
                                                <button type="submit" class="btn btn-primary" id="alert-success">
                                                    Simpan
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <table id="datatable" class="table table-bordered dt-responsive w-100 no-wrap">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 20px">No.</th>
                                            <th class="text-center" style="width: 300px">Nama Unit</th>
                                            <th class="text-center" style="width: 70px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($units as $unit)
                                            <tr class="align-middle">
                                                <td class="text-center">{{ $loop->iteration }}</td>
                                                <td>{{ $unit->nama_unit }}</td>
                                                <td>
                                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                                        <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#editUnitModal{{ $unit->id_unit }}"
                                                            class="btn btn-outline-warning btn-sm edit " title="Edit">
                                                            <i class="fas fa-pencil-alt"></i>
                                                        </a>
                                                        {{-- modal edit unit --}}
                                                        <div class="modal fade" id="editUnitModal{{ $unit->id_unit }}"
                                                            data-bs-backdrop="static" data-bs-keyboard="false"
                                                            tabindex="-1" role="dialog"
                                                            aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="staticBackdropLabel">
                                                                            Edit Unit
                                                                        </h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <form
                                                                        action="{{ route('unit.update', $unit->id_unit) }}"
                                                                        method="POST">
                                                                        @csrf @method('PUT')
                                                                        <input type="hidden" name="id_unit"
                                                                            value="{{ $unit->id_unit }}">
                                                                        <div class="modal-body">
                                                                            <div class="row mb-2">
                                                                                <label for="nama_unit"
                                                                                    class="col-sm-3 col-form-label">Nama
                                                                                    Unit <span class="text-danger">*</span></label>
                                                                                <div class="col-sm-9">
                                                                                    <input type="text"
                                                                                        class="form-control"
                                                                                        id="nama_unit" name="nama_unit"
                                                                                        value="{{ old('nama_unit', $unit->nama_unit) }}"
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
                                                                                data-bs-dismiss="modal"
                                                                                id="alert-success">
                                                                                Simpan
                                                                            </button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <form action="{{ route('unit.destroy', $unit->id_unit) }}"
                                                            method="POST" class="delete-unit"
                                                            style="display: inline-block;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit"
                                                                class="btn btn-outline-danger btn-sm ms-2" title="Delete">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </form>
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
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-unit');
            deleteForms.forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi hapus data?',
                        text: 'Data yang dihapus tidak dapat dikembalikan.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#2ab57d',
                        cancelButtonColor: '#fd625e',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
