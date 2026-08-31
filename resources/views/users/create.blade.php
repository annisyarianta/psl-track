@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="mb-4">
        <h3>Tambah User</h3>
        <p class="text-muted">
            Tambahkan pengguna baru ke dalam sistem.
        </p>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">

        <div class="card-body">

            <form action="{{ route('users.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">
                    <label class="form-label">
                        Nama <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="nama"
                           class="form-control"
                           value="{{ old('nama') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Nopeg <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="nopeg"
                           class="form-control"
                           value="{{ old('nopeg') }}"
                           maxlength="4"
                           inputmode="numeric"
                           required>

                    <small class="text-muted">
                        Maksimal 4 digit.
                    </small>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Email <span class="text-danger">*</span>
                    </label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email') }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Role <span class="text-danger">*</span>
                    </label>

                    <select name="role"
                            class="form-select"
                            required>

                        <option value="">
                            -- Pilih Role --
                        </option>

                        <option value="manager"
                            {{ old('role') == 'manager' ? 'selected' : '' }}>
                            Manager
                        </option>

                        <option value="staff"
                            {{ old('role') == 'staff' ? 'selected' : '' }}>
                            Staff
                        </option>

                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Unit
                    </label>

                    <input type="text"
                           name="unit"
                           class="form-control"
                           value="{{ old('unit') }}">

                    <small class="text-muted">
                        Opsional.
                    </small>
                </div>

                <div class="alert alert-info">
                    <strong>Password awal:</strong>
                    12345678
                    <br>
                    User akan diminta mengganti password
                    setelah login pertama.
                </div>

                <div class="d-flex gap-2">

                    <a href="{{ route('users.index') }}"
                       class="btn btn-secondary">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Simpan User
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection