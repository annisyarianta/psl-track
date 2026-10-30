@extends('layouts.card')

@section('judul', 'Reset Password')

@section('content')
    <h3 class="mb-2 fw-semibold">Reset Password</h3>
    <p class="text-muted font-size-16">
        Silakan masukkan password baru Anda.
    </p>

    @if ($errors->any())
        <div class="alert alert-danger text-start mt-4">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('password.update.reset') }}" method="POST" class="mt-4 text-start">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email', $email) }}" class="form-control" readonly required>
        </div>

        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <div class="input-group auth-pass-inputgroup">
                <input type="password" name="password" class="form-control" placeholder="Masukkan password baru" required>
                <button class="btn btn-light shadow-none ms-0 tombol-tampilkan-password" type="button"
                    aria-label="Tampilkan atau sembunyikan password">
                    <i class="mdi mdi-eye-outline"></i>
                </button>
            </div>
            <small class="text-muted">Minimal 8 karakter.</small>
        </div>

        <div class="mb-4">
            <label class="form-label">Konfirmasi Password</label>
            <div class="input-group auth-pass-inputgroup">
                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi password baru"
                    required>
                <button class="btn btn-light shadow-none ms-0 tombol-tampilkan-password" type="button"
                    aria-label="Tampilkan atau sembunyikan password">
                    <i class="mdi mdi-eye-outline"></i>
                </button>
            </div>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Reset Password
        </button>
    </form>
@endsection