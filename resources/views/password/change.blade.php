<!doctype html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Ubah Password | PSL Track</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-kemenkes.png') }}">
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />

    <style>
        .page-background {
            position: fixed;
            inset: 0;
            z-index: -1;
            background-image: url('{{ asset('assets/images/Tokushukai-1.jpg') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
        }

        .coming-content {
            position: relative;
        }

        .form-card {
            background-color: rgba(255, 255, 255, 0.95);
            -webkit-backdrop-filter: blur(10px);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(0, 190, 180, 0.8);
            border-radius: 16px;
            box-shadow:
                0 0 3px rgba(0, 190, 180, 0.8),
                0 0 8px rgba(0, 190, 180, 0.5),
                0 0 16px rgba(0, 190, 180, 0.3),
                inset 0 0 8px rgba(0, 190, 180, 0.1);
        }

        .form-card .form-control {
            background-color: #ffffff;
            border: 1px solid #ced4da;
        }

        .form-card .form-control:focus {
            border-color: #556ee6;
            box-shadow: 0 0 0 0.2rem rgba(85, 110, 230, 0.2);
        }
    </style>
</head>

<body>
    <!-- background tunggal -->
    <div class="page-background"></div>

    <div class="coming-content min-vh-100 py-4 px-3 py-sm-5">
        <div class="bg-overlay bg-primary"></div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="text-center py-4 py-sm-5">

                        <div class="form-card p-4 p-sm-5">

                            <div class="mb-4">
                                <a href="{{ url('/') }}">
                                    <img src="{{ asset('assets/images/logo-harkit.png') }}" alt="Logo Harkit"
                                        height="60">
                                </a>
                            </div>

                            <h3 class="mb-2 fw-semibold">Ganti Password</h3>
                            <p class="text-muted font-size-16">
                                Untuk keamanan akun, silakan mengganti password default Anda.
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

                            <form action="{{ route('password.update') }}" method="POST" class="mt-4 text-start">
                                @csrf

                                <div class="mb-3">
                                    <label class="form-label">Password Saat Ini</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" name="current_password" class="form-control"
                                            placeholder="Masukkan password saat ini" required>
                                        <button class="btn btn-light shadow-none ms-0 tombol-tampilkan-password"
                                            type="button" aria-label="Tampilkan atau sembunyikan password">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Password Baru</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" name="password" class="form-control"
                                            placeholder="Masukkan password baru" required>
                                        <button class="btn btn-light shadow-none ms-0 tombol-tampilkan-password"
                                            type="button" aria-label="Tampilkan atau sembunyikan password">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </button>
                                    </div>
                                    <small class="text-muted">Minimal 8 karakter.</small>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Konfirmasi Password Baru</label>
                                    <div class="input-group auth-pass-inputgroup">
                                        <input type="password" name="password_confirmation" class="form-control"
                                            placeholder="Ulangi password baru" required>
                                        <button class="btn btn-light shadow-none ms-0 tombol-tampilkan-password"
                                            type="button" aria-label="Tampilkan atau sembunyikan password">
                                            <i class="mdi mdi-eye-outline"></i>
                                        </button>
                                    </div>
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
    </div>

    <!-- JAVASCRIPT -->
<script src="{{ asset('assets/libs/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

<script>
    document.querySelectorAll('.tombol-tampilkan-password').forEach(function (tombolTampilkanPassword) {
        tombolTampilkanPassword.addEventListener('click', function () {
            const kolomPassword = tombolTampilkanPassword.closest('.input-group').querySelector('input');
            const ikonMata = tombolTampilkanPassword.querySelector('i');
            const sedangTersembunyi = kolomPassword.type === 'password';

            kolomPassword.type = sedangTersembunyi ? 'text' : 'password';
            ikonMata.classList.toggle('mdi-eye-outline', !sedangTersembunyi);
            ikonMata.classList.toggle('mdi-eye-off-outline', sedangTersembunyi);
        });
    });
</script>

</body>

</html>
