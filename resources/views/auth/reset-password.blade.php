@extends('layouts.app')

@section('content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card">

                <div class="card-body p-4">

                    <h4>Reset Password</h4>

                    <p class="text-muted">
                        Silakan masukkan password baru Anda.
                    </p>

                    @if($errors->any())

                        <div class="alert alert-danger">

                            <ul class="mb-0">

                                @foreach($errors->all() as $error)

                                    <li>{{ $error }}</li>

                                @endforeach

                            </ul>

                        </div>

                    @endif

                    <form action="{{ route('password.update.reset') }}"
                          method="POST">

                        @csrf

                        <input type="hidden"
                               name="token"
                               value="{{ $token }}">

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ $email }}"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Password Baru
                            </label>

                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Konfirmasi Password
                            </label>

                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   required>

                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100">

                            Reset Password

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection