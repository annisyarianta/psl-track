@extends('layouts.app')

@section('title')
    User Management | PSL Track
@endsection

@section('content')
    {{-- @if (session('success'))
    <script>
        alertify.success(@json(session('success')));
    </script>
    @endif

    @if (session('error'))
        <script>
            alertify.error(@json(session('error')));
        </script>
    @endif --}}

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">
                                Data Pengguna
                            </h3>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">Settings</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        <a href="{{ route('users.index') }}">User Management</a>
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
                                    onclick="window.location.href='{{ route('users.create') }}'"><i
                                        class="bx bx-plus label-icon"></i>Tambah Data</button>
                            </div>
                            <div class="card-body">
                                <table id="datatable" class="table table-bordered dt-responsive w-100 table-hover">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 50px">No. Pegawai</th>
                                            <th class="text-center" style="width: 150px">Nama</th>
                                            <th class="text-center" style="width: 80px">Email</th>
                                            <th class="text-center" style="width: 80px">Unit</th>
                                            <th class="text-center" style="width: 50px">Role</th>
                                            <th class="text-center" style="width: 70px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($users as $user)
                                            <tr>
                                                <td class="text-center">{{ $user->nopeg }}</td>
                                                <td class="text-center">{{ $user->nama }}</td>
                                                <td class="text-center"> {{ $user->email }}</td>
                                                <td class="text-center">{{ $user->unit ?? '-' }}</td>
                                                <td class="text-center">
                                                    @if ($user->role === 'manager')
                                                        <span class="badge bg-primary">
                                                            Manager
                                                        </span>
                                                    @else
                                                        <span class="badge bg-secondary">
                                                            Staff
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('users.edit', $user->id_user) }}"
                                                        class="btn btn-outline-warning btn-sm edit mb-3" title="Edit">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    @if (auth()->id() !== $user->id_user)
                                                        <form action="{{ route('users.destroy', $user->id_user) }}"
                                                            method="POST" class="form-delete-user"
                                                            style="display: inline-block;">

                                                            @csrf
                                                            @method('DELETE')

                                                            <button type="submit"
                                                                class="btn btn-outline-danger btn-sm ms-2 mb-3"
                                                                title="Delete">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>

                                                        </form>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted py-4">
                                                    Belum ada user.
                                                </td>
                                            </tr>
                                        @endforelse
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

            const deleteForms = document.querySelectorAll('.form-delete-user');

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
