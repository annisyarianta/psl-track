@extends('layouts.app')

@section('title')
    Edit User | PSL Track
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">
                                Edit Pengguna
                            </h3>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">Settings</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('users.index') }}">User Management</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Edit Pengguna
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ route('users.update', $user) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="row mb-3">
                                        <label for="nama-input" class="col-sm-2 col-form-label">Nama Lengkap <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nama-input" name="nama"
                                                value="{{ old('nama', $user->nama) }}" placeholder="Masukkan nama pengguna"
                                                required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="nopeg-input" class="col-sm-2 col-form-label">No. Pegawai <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nopeg-input" name="nopeg"
                                                value="{{ old('nopeg', $user->nopeg) }}"
                                                placeholder="Masukkan nomor pegawai pengguna" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="email-input" class="col-sm-2 col-form-label">Email <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="email" class="form-control" id="email-input" name="email"
                                                value="{{ old('email', $user->email) }}"
                                                placeholder="Masukkan email pengguna" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="unit-input" class="col-sm-2 col-form-label">Unit</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="unit-input" name="unit">
                                                <option value=""
                                                    {{ old('unit', $user->unit) == NULL ? 'selected' : '' }}>-- Pilih Unit --
                                                </option>
                                                <option value="perencanaan"
                                                    {{ old('unit', $user->unit) == 'perencanaan' ? 'selected' : '' }}>
                                                    Perencanaan Strategi Bisnis dan Pemasaran
                                                </option>
                                                <option value="inovasi"
                                                    {{ old('unit', $user->unit) == 'inovasi' ? 'selected' : '' }}>
                                                    Inovasi Layanan dan Pengembangan Usaha
                                                </option>
                                                <option value="evaluasi"
                                                    {{ old('unit', $user->unit) == 'evaluasi' ? 'selected' : '' }}>
                                                    Evaluasi Strategi Bisnis dan Pemasaran
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="role-input" class="col-sm-2 col-form-label">Role <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="role-input" name="role" required>
                                                <option value="manager"
                                                    {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>
                                                    Manager
                                                </option>
                                                <option value="asmen"
                                                    {{ old('role', $user->role) == 'asmen' ? 'selected' : '' }}>
                                                    Asisten Manager
                                                </option>
                                                <option value="staff"
                                                    {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>
                                                    Staff
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-secondary w-md"
                                                    onclick="window.location.href='{{ route('users.index') }}'">Batal</button>
                                                <button type="submit" class="btn btn-primary w-md ms-4">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
