@extends('layouts.app')

@section('title')
    Tambah User | PSL Track
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title fs-5 mb-0">Ubah Password</h3>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('profile.password.update') }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="mb-3">
                                        <label for="password_saat_ini" class="form-label">Password Saat Ini</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" id="password_saat_ini" name="password_saat_ini"
                                                class="form-control @error('password_saat_ini') is-invalid @enderror"
                                                required>
                                            <button class="btn btn-light shadow-none ms-0 tombol-tampilkan-password"
                                                type="button" aria-label="Tampilkan atau sembunyikan password">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>
                                            @error('password_saat_ini')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_baru" class="form-label">Password Baru</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" id="password_baru" name="password_baru"
                                                class="form-control @error('password_baru') is-invalid @enderror" required>
                                            <button class="btn btn-light shadow-none ms-0 tombol-tampilkan-password"
                                                type="button" aria-label="Tampilkan atau sembunyikan password">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>
                                            @error('password_baru')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="konfirmasi_password_baru" class="form-label">Konfirmasi Password
                                            Baru</label>
                                        <div class="input-group auth-pass-inputgroup">
                                            <input type="password" id="konfirmasi_password_baru"
                                                name="konfirmasi_password_baru"
                                                class="form-control @error('konfirmasi_password_baru') is-invalid @enderror"
                                                required>
                                            <button class="btn btn-light shadow-none ms-0 tombol-tampilkan-password"
                                                type="button" aria-label="Tampilkan atau sembunyikan password">
                                                <i class="mdi mdi-eye-outline"></i>
                                            </button>
                                            @error('konfirmasi_password_baru')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary">Simpan Password</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
@endsection
