<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Login - PSL Track</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body>

<div class="container">

    <div class="row justify-content-center align-items-center"
         style="min-height: 100vh;">

        <div class="col-md-5">

            <div class="card shadow-sm">

                <div class="card-body p-4">

                    <div class="text-center mb-4">

                        <h3>PSL Track</h3>

                        <p class="text-muted">
                            Silakan login untuk melanjutkan.
                        </p>

                    </div>

                    @if($errors->any())

                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>

                    @endif

                    <form action="{{ route('login') }}"
                          method="POST">

                        @csrf

                        <div class="mb-3">

                            <label class="form-label">
                                Email
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email') }}"
                                   required
                                   autofocus>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="mb-3 text-end">

                            <a href="{{ route('password.request') }}">
                                Lupa password?
                            </a>

                        </div>

                        <button type="submit"
                                class="btn btn-primary w-100">
                            Login
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>