@extends('layouts.app')

@section('title')
    Form Tambah Data Baru | PSL Track
@endsection

@section('content')
    <style>
        #basic-pills-wizard .tab-pane {
            display: none;
        }

        #basic-pills-wizard .tab-pane.active {
            display: block;
        }

        #basic-pills-wizard .tab-pane {
            display: none;
        }

        #basic-pills-wizard .tab-pane.active {
            display: block;
        }
    </style>

    {{-- @if (session('success'))
        <script>
            alertify.success(@json(session('success')));
        </script>
    @endif
    @if (session('error'))
        <script>
            alertify.error(@json(session('error')));
        </script>
    @endif --}}

    {{-- ========================================= --}}
    {{-- ALERT SUCCESS --}}
    {{-- ========================================= --}}

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif


    {{-- ========================================= --}}
    {{-- ALERT ERROR --}}
    {{-- ========================================= --}}

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="ri-error-warning-line me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif


    {{-- ========================================= --}}
    {{-- VALIDATION ERROR --}}
    {{-- ========================================= --}}

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
                <div class="me-2">
                    <i class="ri-error-warning-line fs-18"></i>
                </div>
                <div>
                    <strong>
                        Terdapat kesalahan pada input:
                    </strong>
                    <ul class="mb-0 mt-1">
                        @foreach ($errors->all() as $error)
                            <li>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
            </button>
        </div>
    @endif

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">
                                Form Tambah Data KPI
                            </h3>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">KPI Tracker</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('kpi.show', $kpi->id_kpi) }}">KPI {{ $kpi->tahun }}</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        <a href="{{ route('kpi.struktur.create', $kpi->id_kpi) }}">Tambah Struktur</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-1">KPI {{ $kpi->tahun }}</h4>
                                <p class="text-muted mb-0">
                                    {{ $kpi->judul_kpi }}
                                </p>
                            </div>
                            <div class="card-body">
                                <div id="basic-pills-wizard" class="twitter-bs-wizard">
                                    <ul class="twitter-bs-wizard-nav">
                                        <li class="nav-item">
                                            <a href="#tambah_sasaran" class="nav-link" data-bs-toggle="tab">
                                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Sasaran Program">
                                                    1 </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#tambah_program" class="nav-link" data-bs-toggle="tab">
                                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Program">
                                                    2 </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#tambah_indikator" class="nav-link" data-bs-toggle="tab">
                                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Indikator Program">
                                                    3 </div>
                                            </a>
                                        </li>
                                        <li class="nav-item">
                                            <a href="#review" class="nav-link" data-bs-toggle="tab">
                                                <div class="step-icon" data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Review Form">
                                                    4 </div>
                                            </a>
                                        </li>
                                    </ul>
                                    <!-- wizard-nav -->

                                    {{-- ========================================= --}}
                                    {{-- WIZARD --}}
                                    {{-- ========================================= --}}

                                    <div class="tab-content twitter-bs-wizard-tab-content">
                                        <form action="{{ route('kpi.struktur.store', $kpi->id_kpi) }}" method="POST"
                                            id="kpiStructureForm">
                                            @csrf
                                            <!-- sasaran -->
                                            <div class="tab-pane" id="tambah_sasaran">
                                                <div class="text-center mb-5">
                                                    <h5>Tambah Sasaran Program</h5>
                                                    <p class="card-title-desc">Masukkan sasaran program untuk KPI
                                                        {{ $kpi->tahun }}</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="nama_sasaran" class="form-label">Nama Sasaran <span
                                                                    class="text-danger">*</span>
                                                            </label>
                                                            <input type="text" class="form-control" id="nama_sasaran"
                                                                name="nama_sasaran" placeholder="Masukkan nama sasaran"
                                                                required>
                                                            <div class="invalid-feedback" id="error_nama_sasaran">
                                                                Nama sasaran wajib diisi.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="pager wizard twitter-bs-wizard-pager-link">
                                                    <li class="next"><button type="button" class="btn btn-primary"
                                                            onclick="nextStep(2)">Berikutnya
                                                            <i class="bx bx-chevron-right ms-1"></i></button></li>
                                                </ul>
                                            </div>
                                            <!-- program -->
                                            <div class="tab-pane" id="tambah_program">
                                                <div class="text-center mb-5">
                                                    <h5>Tambah Program</h5>
                                                    <p class="card-title-desc">Masukkan program yang berada di
                                                        bawah sasaran tersebut.</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="nama_program" class="form-label">Nama Program
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="text" class="form-control" id="nama_program"
                                                                name="nama_program" placeholder="Masukkan nama program"
                                                                required>
                                                            <div class="invalid-feedback" id="error_nama_program">
                                                                Nama program wajib diisi.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="pager wizard twitter-bs-wizard-pager-link">
                                                    <li class="previous"><button type="button" class="btn btn-secondary"
                                                            onclick="previousStep(1)"><i
                                                                class="bx bx-chevron-left me-1"></i> Sebelumnya</button>
                                                    </li>
                                                    <li class="next"><button type="button" class="btn btn-primary"
                                                            onclick="nextStep(3)">Berikutnya <i
                                                                class="bx bx-chevron-right ms-1"></i></button></li>
                                                </ul>
                                            </div>
                                            <!-- indikator -->
                                            <div class="tab-pane" id="tambah_indikator">
                                                <div class="text-center mb-5">
                                                    <h5>Tambah Indikator Program</h5>
                                                    <p class="card-title-desc">Masukkan detail indikator program.</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="nama_indikator" class="form-label">Nama Indikator
                                                                Program
                                                                <span class="text-danger">*</span>
                                                            </label>
                                                            <input type="text"
                                                                class="form-control @error('nama_indikator') is-invalid @enderror"
                                                                id="nama_indikator" name="nama_indikator"
                                                                placeholder="Masukkan nama indikator" required>
                                                            <div class="invalid-feedback" id="error_nama_indikator">
                                                                Nama indikator wajib diisi.
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="target" class="form-label">Target
                                                            </label>
                                                            <input type="text"
                                                                class="form-control @error('target') is-invalid @enderror"
                                                                id="target" name="target" placeholder="Contoh: 90%">
                                                            @error('target')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="aspek" class="form-label">Aspek</label>
                                                            <select
                                                                class="form-select @error('aspek') is-invalid @enderror"
                                                                id="aspek" name="aspek">
                                                                <option value="">
                                                                    Pilih Aspek
                                                                </option>
                                                                @foreach ($aspekOptions as $aspek)
                                                                    <option value="{{ $aspek }}"
                                                                        {{ old('aspek') == $aspek ? 'selected' : '' }}>
                                                                        {{ $aspek }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            @error('aspek')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="periode_pengukuran" class="form-label">Periode
                                                                Pengukuran</label>
                                                            <input type="text"
                                                                class="form-control @error('periode_pengukuran') is-invalid @enderror"
                                                                id="periode_pengukuran" name="periode_pengukuran"
                                                                value="{{ old('periode_pengukuran') }}"
                                                                placeholder="Contoh: Triwulanan">
                                                            @error('periode_pengukuran')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        <div class="mb-3">
                                                            <label for="upaya" class="form-label">Upaya</label>
                                                            <textarea class="form-control @error('upaya') is-invalid @enderror" id="upaya" name="upaya" rows="3"
                                                                placeholder="Masukkan upaya pencapaian indikator">{{ old('upaya') }}</textarea>
                                                            @error('upaya')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="pic_indikator" class="form-label">PIC</label>
                                                            <select class="form-control" name="id_user[]" id="pic_indikator" multiple
                                                                placeholder="Pilih PIC">
                                                                @foreach ($users as $user)
                                                                    <option value="{{ $user->id_user }}"
                                                                        {{ in_array($user->id_user, old('id_user', [])) ? 'selected' : '' }}>
                                                                        {{ $user->nama }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="mb-3">
                                                            <label for="due_date" class="form-label">Tenggat Waktu</label>
                                                            <input type="date"
                                                                class="form-control @error('due_date') is-invalid @enderror"
                                                                id="due_date" name="due_date"
                                                                value="{{ old('due_date') }}">
                                                            @error('due_date')
                                                                <div class="invalid-feedback">
                                                                    {{ $message }}
                                                                </div>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="pager wizard twitter-bs-wizard-pager-link">
                                                    <li class="previous"><button type="button" class="btn btn-secondary"
                                                            onclick="previousStep(2)"><i
                                                                class="bx bx-chevron-left me-1"></i> Sebelumnya</button>
                                                    </li>
                                                    <li class="next"><button type="button" class="btn btn-primary"
                                                            onclick="nextStep(4)">Berikutnya <i
                                                                class="bx bx-chevron-right ms-1"></i></button></li>
                                                </ul>
                                            </div>
                                            <!-- review -->
                                            <div class="tab-pane" id="review">
                                                <div class="text-center mb-5">
                                                    <h5>Review Form</h5>
                                                    <p class="card-title-desc">Periksa kembali data yang Anda input.</p>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-12">
                                                        {{-- SASARAN --}}
                                                        <div class="card border shadow-none mb-3">
                                                            <div class="card-body">
                                                                <h5 class="card-title mb-3">
                                                                    <i class="bx bx-list-ul me-1"></i>
                                                                    Sasaran Program
                                                                </h5>
                                                                <p class="mb-0" id="review_sasaran">
                                                                    -
                                                                </p>
                                                            </div>
                                                        </div>

                                                        {{-- PROGRAM --}}
                                                        <div class="card border shadow-none mb-3">
                                                            <div class="card-body">
                                                                <h5 class="card-title mb-3">
                                                                    <i class="bx bx-book-bookmark me-1"></i>
                                                                    Program
                                                                </h5>
                                                                <p class="mb-0" id="review_program">
                                                                    -
                                                                </p>
                                                            </div>
                                                        </div>

                                                        {{-- INDIKATOR --}}
                                                        <div class="card border shadow-none mb-3">
                                                            <div class="card-body">
                                                                <h5 class="card-title mb-3">
                                                                    <i class="bx bxs-bank me-1"></i>
                                                                    Indikator Program
                                                                </h5>
                                                                <div class="table-responsive">
                                                                    <table class="table table-bordered mb-0">
                                                                        <tbody>
                                                                            <tr>
                                                                                <th width="30%">Nama Indikator</th>
                                                                                <td id="review_indikator">-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Target</th>
                                                                                <td id="review_target">-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Aspek</th>
                                                                                <td id="review_aspek">-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Periode Pengukuran</th>
                                                                                <td id="review_periode">-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Upaya</th>
                                                                                <td id="review_upaya">-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>PIC</th>
                                                                                <td id="review_pic">-</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <th>Tenggat Waktu</th>
                                                                                <td id="review_due_date">-</td>
                                                                            </tr>
                                                                        </tbody>
                                                                    </table>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <ul class="pager wizard twitter-bs-wizard-pager-link">
                                                    <li class="previous"><button type="button" class="btn btn-secondary"
                                                            onclick="previousStep(3)"><i
                                                                class="bx bx-chevron-left me-1"></i>
                                                            Sebelumnya</button>
                                                    </li>
                                                    <li class="float-end">
                                                        <button type="submit" class="btn btn-primary" id="btnSubmitKpi">
                                                            Simpan
                                                        </button>
                                                    </li>
                                                </ul>
                                            </div>
                                        </form>
                                    </div>
                                    <!-- end tab content -->
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>
                    <!-- end col -->
                </div>
                <!-- end row -->
            </div>
        </div>
    </div>

    <script>
        function showStep(step) {
            const tabs = document.querySelectorAll(
                '#basic-pills-wizard .nav-link'
            );
            const panes = document.querySelectorAll(
                '#basic-pills-wizard .tab-pane'
            );
            tabs.forEach(tab => {
                tab.classList.remove('active');
            });
            panes.forEach(pane => {
                pane.classList.remove('active', 'show');
            });
            const targetTab = tabs[step - 1];

            if (targetTab) {
                targetTab.classList.add('active');
            }

            if (targetTab) {
                const targetId = targetTab.getAttribute('href');
                const targetPane = document.querySelector(targetId);
                if (targetPane) {
                    targetPane.classList.add('active', 'show');
                }
            }
        }

        function nextStep(step) {

            // Ambil tab yang sedang aktif
            const activePane = document.querySelector(
                '#basic-pills-wizard .tab-pane.active'
            );

            // Ambil semua field required pada step aktif
            const requiredFields = activePane.querySelectorAll(
                'input[required], select[required], textarea[required]'
            );

            let isValid = true;

            requiredFields.forEach(function(field) {

                if (field.value.trim() === '') {

                    // Tandai input sebagai invalid
                    field.classList.add('is-invalid');

                    isValid = false;

                } else {

                    // Hapus tanda invalid jika sudah diisi
                    field.classList.remove('is-invalid');

                }

            });

            // Jika masih ada yang kosong, jangan pindah step
            if (!isValid) {
                return;
            }

            // Jika semua sudah diisi, pindah ke step berikutnya
            showStep(step);

            // Update data review
            updateReview();
        }

        document.querySelectorAll(
            '#kpiStructureForm input, #kpiStructureForm select, #kpiStructureForm textarea'
        ).forEach(function(field) {

            field.addEventListener('input', function() {
                if (this.value.trim() !== '') {
                    this.classList.remove('is-invalid');
                }
            });

            field.addEventListener('change', function() {
                if (this.value.trim() !== '') {
                    this.classList.remove('is-invalid');
                }
            });

        });

        function previousStep(step) {
            showStep(step);
        }


        // =========================
        // UPDATE REVIEW
        // =========================
        function updateReview() {
            document.getElementById('review_sasaran').textContent =
                document.getElementById('nama_sasaran').value || '-';

            document.getElementById('review_program').textContent =
                document.getElementById('nama_program').value || '-';

            document.getElementById('review_indikator').textContent =
                document.getElementById('nama_indikator').value || '-';

            document.getElementById('review_target').textContent =
                document.getElementById('target').value || '-';

            document.getElementById('review_aspek').textContent =
                document.getElementById('aspek').value || '-';

            document.getElementById('review_periode').textContent =
                document.getElementById('periode_pengukuran').value || '-';

            document.getElementById('review_upaya').textContent =
                document.getElementById('upaya').value || '-';

            const pic = document.getElementById('pic_indikator');

            document.getElementById('review_pic').textContent =
                pic.options[pic.selectedIndex]?.text || '-';

            document.getElementById('review_due_date').textContent =
                document.getElementById('due_date').value || '-';
        }


        // =========================
        // SWEETALERT SUBMIT
        // =========================
        document.getElementById('kpiStructureForm').addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Simpan Data KPI?',
                text: 'Pastikan seluruh data yang dimasukkan sudah benar.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Simpan',
                cancelButtonText: 'Batal',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new Choices('#pic_indikator', {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Tambah PIC',
                searchEnabled: true
            });
        });
    </script>
@endsection
