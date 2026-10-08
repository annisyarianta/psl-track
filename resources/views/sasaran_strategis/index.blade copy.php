@extends('layouts.app')

@section('title')
    Sasaran Strategis | PSL Track
@endsection

@section('content')
    @if (session('success'))
        <script>
            alertify.success(@json(session('success')));
        </script>
    @endif

    @if (session('error'))
        <script>
            alertify.error(@json(session('error')));
        </script>
    @endif
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-end">
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        Tracker
                                    </li>
                                    <li class="breadcrumb-item">
                                        KPI Tracker
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Dir. Utama
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row g-3">
                    {{-- KIRI : SASARAN STRATEGIS --}}
                    <div class="col-xl-5 col-lg-5">
                        <div class="card shadow-sm h-100">
                            <div class="card-header border-bottom-dashed">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <div>
                                        <h4 class="card-title mb-1">
                                            Sasaran Strategis
                                        </h4>
                                        <p class="text-muted mb-0 fs-13">
                                            Daftar sasaran strategis
                                        </p>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <select class="form-select form-select-sm" style="min-width: 90px;"
                                            id="filterTahun">
                                            <option value="" disabled selected>Tahun</option>
                                            @foreach ($tahunList as $tahun)
                                                <option value="{{ $tahun->id_tahun }}">
                                                    {{ $tahun->tahun }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="button" data-bs-toggle="modal"
                                            data-bs-target="#tambahSasaranStrategis"
                                            class="btn btn-primary btn-sm d-inline-flex align-items-center flex-shrink-0 text-nowrap">
                                            <i class="bx bx-plus me-1" style="font-size: inherit;"></i>
                                            Tambah
                                        </button>
                                    </div>
                                    {{-- modal tambah sasaran strategis --}}
                                    <div class="modal fade" id="tambahSasaranStrategis" data-bs-backdrop="static"
                                        data-bs-keyboard="false" tabindex="-1" role="dialog"
                                        aria-labelledby="staticBackdropLabel" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="staticBackdropLabel">
                                                        Tambah Sasaran Strategis
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                                </div>
                                                <form action="{{ route('sasaran-strategis.store') }}" method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="row mb-3">
                                                            <label for="tahun" class="col-sm-3 col-form-label">Tahun
                                                                <span class="text-danger">*</span></label>
                                                            <div class="col-sm-9">
                                                                <select class="form-select" id="tahun" name="id_tahun"
                                                                    required>
                                                                    <option value="" disabled selected>--- Pilih Tahun
                                                                        ---</option>
                                                                    @foreach ($tahunList as $tahun)
                                                                        <option value="{{ $tahun->id_tahun }}"
                                                                            {{ old('id_tahun') == $tahun->id_tahun ? 'selected' : '' }}>
                                                                            {{ $tahun->tahun }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row mb-2">
                                                            <label for="nama_sasaran_strategis"
                                                                class="col-sm-3 col-form-label">Nama Sasaran Strategis
                                                                <span class="text-danger">*</span></label>
                                                            <div class="col-sm-9">
                                                                <textarea class="form-control" id="nama_sasaran_strategis" name="nama_sasaran_strategis" rows="4" required>{{ old('nama_sasaran_strategis') }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-bs-dismiss="modal">
                                                            Batal
                                                        </button>
                                                        <button type="submit" class="btn btn-primary" id="alert-success">
                                                            Simpan
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                <div class="list-group list-group-flush" id="sasaranStrategisList">
                                    @forelse($sasaranStrategis ?? [] as $index => $sasaran)
                                        <div class="list-group-item sasaran-item p-3"
                                            data-sasaran-id="{{ $sasaran->id_sasaran }}" role="button">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm">
                                                        <div
                                                            class="avatar-title rounded bg-primary-subtle text-primary fw-semibold">
                                                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 min-w-0">
                                                    <div class="d-flex align-items-start justify-content-between gap-2">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 text-dark fw-semibold">
                                                                {{ $sasaran->nama_sasaran_strategis }}
                                                            </h6>
                                                            <div class="d-flex flex-wrap align-items-center gap-2">
                                                                <span class="text-muted fs-12">
                                                                    <i class="bx bx-chart me-1"></i>
                                                                    {{ $sasaran->indikatorKpi->count() ?? 0 }}
                                                                    indikator
                                                                </span>
                                                                <span class="text-muted fs-12">
                                                                    <i class="bx bx-sitemap me-1"></i>
                                                                    {{ $sasaran->indikatorKpi->sum(fn($kpi) => $kpi->sasaranInisiatif->count()) }}
                                                                    inisiatif
                                                                </span>
                                                            </div>
                                                        </div>
                                                        {{-- ACTION --}}
                                                        <div class="dropdown flex-shrink-0"
                                                            onclick="event.stopPropagation();">
                                                            <button class="btn btn-ghost-secondary btn-sm" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bx bx-dots-vertical-rounded fs-16"></i> </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <a class="dropdown-item"
                                                                        href="{{ route('sasaran-strategis.edit', $sasaran->id_sasaran_strategis) }}">
                                                                        <i class="bx bx-edit me-2"></i>
                                                                        Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                <li>
                                                                    <form
                                                                        action="{{ route('sasaran-strategis.destroy', $sasaran->id_sasaran_strategis) }}"
                                                                        method="POST" class="form-delete">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="dropdown-item text-danger">
                                                                            <i class="bx bx-trash me-2"></i>
                                                                            Hapus
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    {{-- DETAIL LINK --}}
                                                    <div class="mt-2">
                                                        <span class="text-primary fs-13 fw-medium">
                                                            Lihat indikator
                                                            <i class="bx bx-right-arrow-alt align-middle ms-1"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5 px-3">
                                            <div class="avatar-md mx-auto mb-3">
                                                <div class="avatar-title rounded-circle bg-light text-muted">
                                                    <i class="bx bx-loader-circle bx-sm"></i>
                                                </div>
                                            </div>
                                            <h6 class="mb-1">
                                                Belum ada Sasaran Strategis
                                            </h6>
                                            <p class="text-muted fs-13 mb-3">
                                                Tambahkan sasaran strategis untuk mulai mengelola KPI.
                                            </p>
                                            <a href="{{ route('sasaran-strategis.create') }}"
                                                class="btn btn-primary btn-sm">
                                                Tambah Sasaran
                                            </a>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-7 col-lg-7">
                        <div class="card shadow-sm h-100">
                            <div id="indikatorEmptyState" class="h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center px-4 py-5">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                            <i class="bx bx-collection bx-md"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-2">
                                        Pilih Sasaran Strategis
                                    </h5>
                                    <p class="text-muted mb-0 fs-13">
                                        Klik salah satu sasaran strategis di sebelah kiri
                                        untuk melihat indikator KPI yang terkait.
                                    </p>
                                </div>
                            </div>

                            <div id="indikatorDetail" class="d-none">
                                <div class="card-header border-bottom-dashed">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                        <div>
                                            <div class="d-flex align-items-center gap-2 mb-1">
                                                <span class="badge bg-primary-subtle text-primary">
                                                    Sasaran Strategis
                                                </span>
                                            </div>
                                            <h5 class="card-title mb-1" id="selectedSasaranNama">
                                                Nama Sasaran Strategis
                                            </h5>

                                            <p class="text-muted fs-13 mb-0">
                                                Daftar indikator KPI berdasarkan sasaran
                                                strategis yang dipilih.
                                            </p>
                                        </div>

                                        <div>
                                            <a href="#" id="btnTambahIndikator" class="btn btn-primary btn-sm">
                                                <i class="bx bx-plus align-middle me-1"></i> Tambah
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body p-0">
                                    <div id="indikatorList" class="list-group list-group-flush">
                                        {{-- 
                                Contoh struktur yang nantinya dapat
                                di-render menggunakan Blade / AJAX:\
                                @foreach ($indikatorPrograms as $indikator)
                            --}}
                                        <div class="list-group-item p-3">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar-sm">
                                                        <div
                                                            class="avatar-title rounded bg-info-subtle text-info fw-semibold">
                                                            01
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- CONTENT --}}
                                                <div class="flex-grow-1 min-w-0">
                                                    <div class="d-flex align-items-start justify-content-between gap-2">
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1 text-dark fw-semibold">
                                                                Nama Indikator KPI 1
                                                            </h6>
                                                            <div class="mb-2">
                                                                <span class="text-muted fs-13">
                                                                    Target:
                                                                </span>
                                                                <span class="fw-semibold fs-13">
                                                                    &lt; 5%
                                                                </span>
                                                            </div>
                                                            <div class="d-flex flex-wrap gap-2">
                                                                <span class="badge bg-light text-muted">
                                                                    <i class="bx bx-sitemap me-1"></i>
                                                                    3 inisiatif
                                                                </span>
                                                                <span class="badge bg-light text-muted">
                                                                    <i class="bx bx-list-check me-1"></i>
                                                                    5 program
                                                                </span>
                                                            </div>
                                                        </div>

                                                        <div class="dropdown flex-shrink-0">
                                                            <button class="btn btn-ghost-secondary btn-sm" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false">
                                                                <i class="bx bx-dots-vertical-rounded fs-16"></i>
                                                            </button>
                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                <li>
                                                                    <a href="#" class="dropdown-item">
                                                                        <i class="bx bx-pencil me-2 text-muted"></i> Edit
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <hr class="dropdown-divider">
                                                                </li>
                                                                <li>
                                                                    <a href="#" class="dropdown-item text-danger">

                                                                        <i class="bx bx-trash me-2"></i>
                                                                        Hapus
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {{-- EMPTY STATE UNTUK INDIKATOR --}}
                                        {{-- 
                            <div class="text-center py-5 px-3">
                                <i class="bx bx-chart fs-36 text-muted"></i>
                                <h6 class="mt-3">
                                    Belum ada indikator
                                </h6>
                                <p class="text-muted fs-13">
                                    Belum terdapat indikator KPI pada sasaran ini.
                                </p>
                            </div>
                            --}}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection


@push('styles')
    <style>
        .sasaran-item {
            transition: all .2s ease;
            border-left: 3px solid transparent !important;
        }

        .sasaran-item:hover {
            background-color: var(--vz-light);
        }

        .sasaran-item.active {
            background-color: rgba(var(--vz-primary-rgb), .06);
            border-left-color: var(--vz-primary) !important;
        }

        .min-w-0 {
            min-width: 0;
        }

        @media (max-width: 991.98px) {
            .card.h-100 {
                height: auto !important;
            }

            #indikatorEmptyState {
                min-height: 300px;
            }

            #indikatorDetail {
                min-height: 300px;
            }
        }


        @media (max-width: 575.98px) {

            .page-title-box {
                display: block !important;
            }

            .page-title-right {
                margin-top: 8px;
            }

            .card-header {
                padding: 1rem;
            }

            .list-group-item {
                padding: 1rem !important;
            }

            .sasaran-item .dropdown,
            #indikatorList .dropdown {
                margin-left: auto;
            }

        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sasaranItems = document.querySelectorAll('.sasaran-item');
            const emptyState = document.getElementById('indikatorEmptyState');
            const detailState = document.getElementById('indikatorDetail');
            const selectedSasaranNama =
                document.getElementById('selectedSasaranNama');
            const btnTambahIndikator =
                document.getElementById('btnTambahIndikator');

            sasaranItems.forEach(function(item) {
                item.addEventListener('click', function() {
                    sasaranItems.forEach(function(el) {
                        el.classList.remove('active');
                    });

                    item.classList.add('active');
                    const sasaranId =
                        item.dataset.sasaranId;

                    const nama =
                        item.querySelector('h6')?.textContent.trim();

                    emptyState.classList.add('d-none');
                    detailState.classList.remove('d-none');
                    selectedSasaranNama.textContent = nama;

                    /*
                     * ======================================================
                     * TODO:
                     * Load indikator berdasarkan sasaranId
                     * ======================================================
                     *
                     * Pilihan:
                     *
                     * 1. Server-side:
                     *    controller sudah mengirim seluruh data
                     *
                     * 2. AJAX:
                     *    fetch('/sasaran-strategis/' + sasaranId + '/indikator')
                     *
                     * 3. Alpine / Livewire
                     *
                     * Sesuaikan dengan arsitektur aplikasi.
                     */


                    /*
                     * Contoh route dinamis untuk tombol tambah.
                     *
                     * SESUAIKAN ROUTE CONTROLLER
                     */

                    btnTambahIndikator.href =
                        `/sasaran-strategis/${sasaranId}/indikator/create`;

                });

            });


            /*
            |--------------------------------------------------------------------------
            | DELETE CONFIRMATION
            |--------------------------------------------------------------------------
            |
            | Jika project menggunakan SweetAlert, bagian ini bisa
            | disesuaikan dengan konfigurasi SweetAlert yang sudah ada.
            |
            */

            document.querySelectorAll('.form-delete').forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();

                    /*
                     * TODO:
                     * Gunakan SweetAlert project.
                     */

                    if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
                        form.submit();
                    }

                });

            });

        });
    </script>
@endpush
