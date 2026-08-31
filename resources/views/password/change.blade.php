@extends('layouts.app')

@section('content')

    <div class="container py-5">

        <div class="row justify-content-center">

            <div class="col-md-6">

                <div class="card shadow-sm">

                    <div class="card-body p-4">

                        <h4 class="mb-2">
                            Ganti Password
                        </h4>

                        <p class="text-muted">
                            Untuk keamanan akun, silakan
                            mengganti password default Anda.
                        </p>

                        @if ($errors->any())
                            <div class="alert alert-danger">

                                <ul class="mb-0">

                                    @foreach ($errors->all() as $error)
                                        <li>
                                            {{ $error }}
                                        </li>
                                    @endforeach

                                </ul>

                            </div>
                        @endif

                        <form action="{{ route('password.update') }}" method="POST">

                            @csrf

                            <div class="mb-3">

                                <label class="form-label">
                                    Password Saat Ini
                                </label>

                                <input type="password" name="current_password" class="form-control" required>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Password Baru
                                </label>

                                <input type="password" name="password" class="form-control" required>

                                <small class="text-muted">
                                    Minimal 8 karakter.
                                </small>

                            </div>

                            <div class="mb-3">

                                <label class="form-label">
                                    Konfirmasi Password Baru
                                </label>

                                <input type="password" name="password_confirmation" class="form-control" required>

                            </div>

                            <button type="submit" class="btn btn-primary w-100">

                                Simpan Password Baru

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection
