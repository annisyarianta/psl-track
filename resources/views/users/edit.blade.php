@extends('layouts.app')

@section('content')

<div class="container py-4">

    <div class="mb-4">
        <h3>Edit User</h3>
        <p class="text-muted">
            Perbarui informasi pengguna.
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

            <form action="{{ route('users.update', $user) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label">
                        Nama <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="nama"
                           class="form-control"
                           value="{{ old('nama', $user->nama) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Nopeg <span class="text-danger">*</span>
                    </label>

                    <input type="text"
                           name="nopeg"
                           class="form-control"
                           value="{{ old('nopeg', $user->nopeg) }}"
                           maxlength="4"
                           inputmode="numeric"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Email <span class="text-danger">*</span>
                    </label>

                    <input type="email"
                           name="email"
                           class="form-control"
                           value="{{ old('email', $user->email) }}"
                           required>
                </div>

                <div class="mb-3">
                    <label class="form-label">
                        Role <span class="text-danger">*</span>
                    </label>

                    <select name="role"
                            class="form-select"
                            required>

                        <option value="manager"
                            {{ old('role', $user->role) == 'manager' ? 'selected' : '' }}>
                            Manager
                        </option>

                        <option value="staff"
                            {{ old('role', $user->role) == 'staff' ? 'selected' : '' }}>
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
                           value="{{ old('unit', $user->unit) }}">

                    <small class="text-muted">
                        Opsional.
                    </small>
                </div>

                <div class="d-flex gap-2">

                    <a href="{{ route('users.index') }}"
                       class="btn btn-secondary">
                        Batal
                    </a>

                    <button type="submit"
                            class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection