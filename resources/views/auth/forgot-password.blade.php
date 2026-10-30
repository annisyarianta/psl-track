@extends('layouts.card')

@section('judul', 'Lupa Password')

@section('content')
    <h3 class="mb-2 fw-semibold">Lupa Password</h3>
    <p class="text-muted font-size-16">
        Masukkan email akun Anda untuk mendapatkan link reset password.
    </p>

    @if (session('success'))
        <div class="alert alert-success text-start mt-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger text-start mt-4">
            {{ $errors->first() }}
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="mt-4 text-start">
        @csrf

        <div class="mb-4">
            <label class="form-label">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                placeholder="Masukkan email akun Anda" required autofocus>
        </div>

        <button type="submit" class="btn btn-primary w-100">
            Kirim Link Reset Password
        </button>
    </form>

    <div class="mt-4">
        <a href="{{ route('login') }}">Kembali ke Login</a>
    </div>
@endsection
