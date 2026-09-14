@extends('layouts.app')

@section('title')
    Form Tambah Data Baru | PSL Track
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
                {{-- ========================================= --}}
                {{-- HEADER --}}
                {{-- ========================================= --}}
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-sm-0">
                                    Tambah Struktur KPI
                                </h4>

                                <p class="text-muted mb-0 mt-1">
                                    Tambahkan Sasaran, Program, dan Indikator
                                </p>
                            </div>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('kpi.index') }}">
                                            KPI Tracker
                                        </a>
                                    </li>

                                    <li class="breadcrumb-item">
                                        <a href="{{ route('kpi.show', $kpi->id_kpi) }}">
                                            KPI {{ $kpi->tahun }}
                                        </a>
                                    </li>

                                    <li class="breadcrumb-item active">
                                        Tambah Struktur
                                    </li>
                                </ol>
                            </div>

                        </div>

                    </div>
                </div>


                {{-- ========================================= --}}
                {{-- INFORMASI KPI --}}
                {{-- ========================================= --}}

                <div class="row">
                    <div class="col-lg-12">

                        <div class="card">

                            <div class="card-body">

                                <div class="d-flex align-items-center">

                                    <div class="flex-grow-1">

                                        <h5 class="card-title mb-1">
                                            KPI {{ $kpi->tahun }}
                                        </h5>

                                        <p class="text-muted mb-0">
                                            {{ $kpi->judul_kpi }}
                                        </p>

                                    </div>

                                    <div>
                                        <span class="badge bg-primary-subtle text-primary fs-6">
                                            {{ $kpi->tahun }}
                                        </span>
                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>
                </div>


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


                {{-- ========================================= --}}
                {{-- WIZARD --}}
                {{-- ========================================= --}}

                <div class="row">

                    <div class="col-xl-12">

                        <div class="card">

                            <div class="card-body">

                                {{-- ================================= --}}
                                {{-- STEP INDICATOR --}}
                                {{-- ================================= --}}

                                <div class="step-arrow-nav mb-4">

                                    <ul class="nav nav-pills custom-nav nav-justified" role="tablist">

                                        <li class="nav-item" role="presentation">

                                            <button type="button" class="nav-link active" id="step-sasaran-tab">

                                                <i class="ri-target-line me-1"></i>

                                                Sasaran

                                            </button>

                                        </li>


                                        <li class="nav-item" role="presentation">

                                            <button type="button" class="nav-link" id="step-program-tab">

                                                <i class="ri-stack-line me-1"></i>

                                                Program

                                            </button>

                                        </li>


                                        <li class="nav-item" role="presentation">

                                            <button type="button" class="nav-link" id="step-indikator-tab">

                                                <i class="ri-bar-chart-line me-1"></i>

                                                Indikator

                                            </button>

                                        </li>

                                    </ul>

                                </div>


                                {{-- ================================= --}}
                                {{-- FORM --}}
                                {{-- ================================= --}}

                                <form action="{{ route('kpi.struktur.store', $kpi->id_kpi) }}" method="POST"
                                    id="kpiStructureForm">

                                    @csrf


                                    {{-- ================================= --}}
                                    {{-- STEP 1 : SASARAN --}}
                                    {{-- ================================= --}}

                                    <div class="wizard-step" id="step-sasaran">

                                        <div class="mb-4">

                                            <h5 class="mb-1">
                                                Tambah Sasaran
                                            </h5>

                                            <p class="text-muted">
                                                Masukkan sasaran program untuk KPI
                                                {{ $kpi->tahun }}.
                                            </p>

                                        </div>


                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="mb-3">

                                                    <label for="nama_sasaran" class="form-label">

                                                        Nama Sasaran
                                                        <span class="text-danger">*</span>

                                                    </label>

                                                    <textarea class="form-control @error('nama_sasaran') is-invalid @enderror" id="nama_sasaran" name="nama_sasaran"
                                                        rows="4" placeholder="Masukkan nama sasaran">{{ old('nama_sasaran') }}</textarea>

                                                    @error('nama_sasaran')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>

                                            </div>

                                        </div>


                                        <div class="d-flex justify-content-end mt-4">

                                            <button type="button" class="btn btn-primary" onclick="nextStep(2)">

                                                Berikutnya

                                                <i class="ri-arrow-right-line ms-1"></i>

                                            </button>

                                        </div>

                                    </div>


                                    {{-- ================================= --}}
                                    {{-- STEP 2 : PROGRAM --}}
                                    {{-- ================================= --}}

                                    <div class="wizard-step d-none" id="step-program">

                                        <div class="mb-4">

                                            <h5 class="mb-1">
                                                Tambah Program
                                            </h5>

                                            <p class="text-muted">
                                                Masukkan program yang berada di
                                                bawah sasaran tersebut.
                                            </p>

                                        </div>


                                        <div class="alert alert-info">

                                            <i class="ri-information-line me-1"></i>

                                            Program yang dibuat pada tahap ini akan
                                            otomatis terhubung dengan sasaran yang
                                            baru saja kamu masukkan.

                                        </div>


                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="mb-3">

                                                    <label for="nama_program" class="form-label">

                                                        Nama Program
                                                        <span class="text-danger">*</span>

                                                    </label>

                                                    <textarea class="form-control @error('nama_program') is-invalid @enderror" id="nama_program" name="nama_program"
                                                        rows="4" placeholder="Masukkan nama program">{{ old('nama_program') }}</textarea>

                                                    @error('nama_program')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>

                                            </div>

                                        </div>


                                        <div class="d-flex justify-content-between mt-4">

                                            <button type="button" class="btn btn-light" onclick="previousStep(1)">

                                                <i class="ri-arrow-left-line me-1"></i>

                                                Sebelumnya

                                            </button>


                                            <button type="button" class="btn btn-primary" onclick="nextStep(3)">

                                                Berikutnya

                                                <i class="ri-arrow-right-line ms-1"></i>

                                            </button>

                                        </div>

                                    </div>


                                    {{-- ================================= --}}
                                    {{-- STEP 3 : INDIKATOR --}}
                                    {{-- ================================= --}}

                                    <div class="wizard-step d-none" id="step-indikator">

                                        <div class="mb-4">

                                            <h5 class="mb-1">
                                                Tambah Indikator
                                            </h5>

                                            <p class="text-muted">
                                                Masukkan detail indikator program.
                                            </p>

                                        </div>


                                        {{-- NAMA INDIKATOR --}}

                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="mb-3">

                                                    <label for="nama_indikator" class="form-label">

                                                        Nama Indikator
                                                        <span class="text-danger">*</span>

                                                    </label>

                                                    <textarea class="form-control @error('nama_indikator') is-invalid @enderror" id="nama_indikator"
                                                        name="nama_indikator" rows="3" placeholder="Masukkan nama indikator">{{ old('nama_indikator') }}</textarea>

                                                    @error('nama_indikator')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>

                                            </div>

                                        </div>


                                        <div class="row">

                                            {{-- TARGET --}}

                                            <div class="col-lg-6">

                                                <div class="mb-3">

                                                    <label for="target" class="form-label">

                                                        Target

                                                    </label>

                                                    <input type="text"
                                                        class="form-control @error('target') is-invalid @enderror"
                                                        id="target" name="target" value="{{ old('target') }}"
                                                        placeholder="Contoh: 90%">

                                                    @error('target')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>

                                            </div>


                                            {{-- ASPEK --}}

                                            <div class="col-lg-6">

                                                <div class="mb-3">

                                                    <label for="aspek" class="form-label">

                                                        Aspek

                                                    </label>

                                                    <select class="form-select @error('aspek') is-invalid @enderror"
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

                                        </div>


                                        <div class="row">

                                            {{-- PERIODE PENGUKURAN --}}

                                            <div class="col-lg-6">

                                                <div class="mb-3">

                                                    <label for="periode_pengukuran" class="form-label">

                                                        Periode Pengukuran

                                                    </label>

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


                                            {{-- DUE DATE --}}

                                            <div class="col-lg-6">

                                                <div class="mb-3">

                                                    <label for="due_date" class="form-label">

                                                        Due Date

                                                    </label>

                                                    <input type="date"
                                                        class="form-control @error('due_date') is-invalid @enderror"
                                                        id="due_date" name="due_date" value="{{ old('due_date') }}">

                                                    @error('due_date')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror

                                                </div>

                                            </div>

                                        </div>


                                        {{-- UPAYA --}}

                                        <div class="row">

                                            <div class="col-lg-12">

                                                <div class="mb-3">

                                                    <label for="upaya" class="form-label">

                                                        Upaya

                                                    </label>

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


                                        {{-- BUTTON --}}

                                        <div class="d-flex justify-content-between mt-4">

                                            <button type="button" class="btn btn-light" onclick="previousStep(2)">

                                                <i class="ri-arrow-left-line me-1"></i>

                                                Sebelumnya

                                            </button>


                                            <button type="submit" class="btn btn-success">

                                                <i class="ri-save-line me-1"></i>

                                                Simpan

                                            </button>

                                        </div>

                                    </div>

                                </form>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>


    {{-- ========================================= --}}
    {{-- WIZARD JAVASCRIPT --}}
    {{-- ========================================= --}}

    <script>
        let currentStep = 1;


        function showStep(step) {

            // Sembunyikan seluruh step
            document.querySelectorAll('.wizard-step').forEach(function(element) {

                element.classList.add('d-none');

            });


            // Tampilkan step yang dipilih
            document
                .getElementById('step-' + getStepName(step))
                .classList.remove('d-none');


            // Reset status nav
            document
                .querySelectorAll('.custom-nav .nav-link')
                .forEach(function(element) {

                    element.classList.remove('active');

                });


            // Aktifkan nav sesuai step
            document
                .getElementById('step-' + getStepName(step) + '-tab')
                .classList.add('active');


            currentStep = step;

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });

        }


        function getStepName(step) {

            if (step === 1) {
                return 'sasaran';
            }

            if (step === 2) {
                return 'program';
            }

            if (step === 3) {
                return 'indikator';
            }

        }


        function nextStep(step) {

            // Validasi sederhana sebelum pindah step
            if (currentStep === 1) {

                const namaSasaran =
                    document.getElementById('nama_sasaran').value.trim();

                if (namaSasaran === '') {

                    alert('Nama Sasaran wajib diisi.');

                    document
                        .getElementById('nama_sasaran')
                        .focus();

                    return;
                }

            }


            if (currentStep === 2) {

                const namaProgram =
                    document.getElementById('nama_program').value.trim();

                if (namaProgram === '') {

                    alert('Nama Program wajib diisi.');

                    document
                        .getElementById('nama_program')
                        .focus();

                    return;
                }

            }


            showStep(step);

        }


        function previousStep(step) {

            showStep(step);

        }


        // Tampilkan step pertama ketika halaman dibuka
        document.addEventListener('DOMContentLoaded', function() {

            showStep(1);

        });
    </script>

@endsection
