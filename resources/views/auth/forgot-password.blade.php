@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card">

                <div class="card-body p-4">

                    <h4>Lupa Password</h4>

                    <p class="text-muted">
                        Masukkan email akun Anda untuk
                        mendapatkan link reset password.
                    </p>

                    @if(session('success'))

                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>

                    @endif

                    @if($errors->any())

                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>

                    @endif

                    <form action="{{ route('password.email') }}"
                          method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   required>

                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100">

                            Kirim Link Reset Password

                        </button>

                    </form>

                    <div class="text-center mt-3">

                        <a href="{{ route('login') }}">
                            Kembali ke Login
                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection