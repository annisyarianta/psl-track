@extends('layouts.app')

@section('title', 'Struktur Kinerja')

@section('content')

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <div>
                                <h4 class="mb-sm-0">Struktur Kinerja</h4>

                                <p class="text-muted mb-0 mt-1">
                                    Melihat hubungan hierarki indikator hingga sasaran strategis.
                                </p>
                            </div>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">

                                    <li class="breadcrumb-item">
                                        <a href="#">
                                            Dashboard
                                        </a>
                                    </li>

                                    <li class="breadcrumb-item">
                                        <a href="#">
                                            KPI & Sasaran
                                        </a>
                                    </li>

                                    <li class="breadcrumb-item">
                                        <a href="#">
                                            {{-- DATA DINAMIS: tahun --}}
                                            Tahun 2026
                                        </a>
                                    </li>

                                    <li class="breadcrumb-item active">
                                        Struktur Kinerja
                                    </li>

                                </ol>
                            </div>

                        </div>
                    </div>
                </div>


                {{-- =========================================================
        ACTION BAR
        ========================================================= --}}
                <div class="row mb-3">

                    <div class="col-12">

                        <div class="card mb-0">
                            <div class="card-body">

                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">

                                    <div>

                                        <h5 class="card-title mb-1">
                                            Struktur Kinerja
                                        </h5>

                                        <p class="text-muted mb-0">
                                            {{-- DATA DINAMIS: nama indikator yang sedang dipilih --}}
                                            Menampilkan struktur dari indikator
                                            <strong>Tingkat Penyelesaian Pengembangan Fitur Laporan</strong>
                                            hingga sasaran strategis.
                                        </p>

                                    </div>

                                    <div class="d-flex gap-2">

                                        <button type="button" class="btn btn-soft-secondary"
                                            onclick="window.history.back()">

                                            <i class="ri-arrow-left-line me-1"></i>
                                            Kembali

                                        </button>

                                        {{-- OPSIONAL --}}
                                        <button type="button" class="btn btn-primary">

                                            <i class="ri-node-tree me-1"></i>
                                            Lihat Diagram

                                        </button>

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                </div>


                {{-- =========================================================
        MAIN CONTENT
        ========================================================= --}}
                <div class="row g-3">


                    {{-- =====================================================
            LEFT : HIERARCHY
            ===================================================== --}}
                    <div class="col-xl-4 col-lg-5">

                        <div class="card h-100">

                            <div class="card-header">

                                <div class="d-flex align-items-center justify-content-between">

                                    <div>

                                        <h5 class="card-title mb-1">
                                            Hierarki Kinerja
                                        </h5>

                                        <p class="text-muted mb-0 fs-13">
                                            Hubungan parent dan child
                                        </p>

                                    </div>

                                    <span class="badge bg-soft-primary text-primary">
                                        7 Level
                                    </span>

                                </div>

                            </div>


                            <div class="card-body">

                                {{-- =================================================
                        YEAR
                        ================================================= --}}
                                <div class="hierarchy-item hierarchy-year">

                                    <div class="hierarchy-line">

                                        <div class="hierarchy-icon bg-soft-primary text-primary">
                                            <i class="ri-calendar-line"></i>
                                        </div>

                                        <div class="hierarchy-content">

                                            <div class="hierarchy-label">
                                                Tahun
                                            </div>

                                            <button type="button" class="hierarchy-title hierarchy-button">

                                                {{-- DATA DINAMIS --}}
                                                2026

                                            </button>

                                            <div class="text-muted fs-12">
                                                Periode kinerja
                                            </div>

                                        </div>

                                    </div>

                                </div>


                                {{-- =================================================
                        SASARAN STRATEGIS
                        ================================================= --}}
                                <div class="hierarchy-item">

                                    <div class="hierarchy-connector"></div>

                                    <button type="button" class="hierarchy-card active">

                                        <div class="d-flex align-items-start gap-3">

                                            <div class="hierarchy-icon bg-soft-primary text-primary flex-shrink-0">
                                                <i class="ri-focus-3-line"></i>
                                            </div>

                                            <div class="flex-grow-1 min-w-0">

                                                <div class="d-flex justify-content-between gap-2">

                                                    <span class="hierarchy-label">
                                                        Sasaran Strategis
                                                    </span>

                                                    <i class="ri-check-line text-primary"></i>

                                                </div>

                                                <div class="hierarchy-title">

                                                    {{-- DATA DINAMIS --}}
                                                    Peningkatan Kualitas Pelayanan

                                                </div>

                                                <div class="hierarchy-meta">

                                                    {{-- DATA DINAMIS --}}
                                                    ID: SS-001
                                                    <span class="mx-1">•</span>
                                                    3 indikator KPI
                                                    <span class="mx-1">•</span>
                                                    5 program

                                                </div>

                                            </div>

                                        </div>

                                    </button>

                                </div>


                                {{-- =================================================
                        SASARAN INISIATIF
                        ================================================= --}}
                                <div class="hierarchy-item">

                                    <div class="hierarchy-connector"></div>

                                    <button type="button" class="hierarchy-card">

                                        <div class="d-flex align-items-start gap-3">

                                            <div class="hierarchy-icon bg-soft-success text-success flex-shrink-0">
                                                <i class="ri-lightbulb-line"></i>
                                            </div>

                                            <div class="flex-grow-1 min-w-0">

                                                <div class="hierarchy-label">
                                                    Sasaran Inisiatif
                                                </div>

                                                <div class="hierarchy-title">

                                                    {{-- DATA DINAMIS --}}
                                                    Digitalisasi Pelayanan

                                                </div>

                                                <div class="hierarchy-meta">

                                                    {{-- DATA DINAMIS --}}
                                                    ID: SI-002
                                                    <span class="mx-1">•</span>
                                                    3 indikator inisiatif
                                                    <span class="mx-1">•</span>
                                                    4 program

                                                </div>

                                            </div>

                                        </div>

                                    </button>

                                </div>


                                {{-- =================================================
                        PROGRAM
                        ================================================= --}}
                                <div class="hierarchy-item">

                                    <div class="hierarchy-connector"></div>

                                    <button type="button" class="hierarchy-card">

                                        <div class="d-flex align-items-start gap-3">

                                            <div class="hierarchy-icon bg-soft-warning text-warning flex-shrink-0">
                                                <i class="ri-file-list-3-line"></i>
                                            </div>

                                            <div class="flex-grow-1 min-w-0">

                                                <div class="hierarchy-label">
                                                    Program
                                                </div>

                                                <div class="hierarchy-title">

                                                    {{-- DATA DINAMIS --}}
                                                    Pengembangan Sistem Informasi

                                                </div>

                                                <div class="hierarchy-meta">

                                                    {{-- DATA DINAMIS --}}
                                                    ID: PR-003
                                                    <span class="mx-1">•</span>
                                                    2 indikator program
                                                    <span class="mx-1">•</span>
                                                    3 kegiatan

                                                </div>

                                            </div>

                                        </div>

                                    </button>

                                </div>


                                {{-- =================================================
                        KEGIATAN
                        ================================================= --}}
                                <div class="hierarchy-item">

                                    <div class="hierarchy-connector"></div>

                                    <button type="button" class="hierarchy-card">

                                        <div class="d-flex align-items-start gap-3">

                                            <div class="hierarchy-icon bg-soft-danger text-danger flex-shrink-0">
                                                <i class="ri-settings-3-line"></i>
                                            </div>

                                            <div class="flex-grow-1 min-w-0">

                                                <div class="hierarchy-label">
                                                    Kegiatan
                                                </div>

                                                <div class="hierarchy-title">

                                                    {{-- DATA DINAMIS --}}
                                                    Pengembangan Modul Monitoring

                                                </div>

                                                <div class="hierarchy-meta">

                                                    {{-- DATA DINAMIS --}}
                                                    ID: KG-005
                                                    <span class="mx-1">•</span>
                                                    2 indikator kegiatan
                                                    <span class="mx-1">•</span>
                                                    4 sub kegiatan

                                                </div>

                                            </div>

                                        </div>

                                    </button>

                                </div>


                                {{-- =================================================
                        SUB KEGIATAN
                        ================================================= --}}
                                <div class="hierarchy-item">

                                    <div class="hierarchy-connector"></div>

                                    <button type="button" class="hierarchy-card">

                                        <div class="d-flex align-items-start gap-3">

                                            <div class="hierarchy-icon bg-soft-info text-info flex-shrink-0">
                                                <i class="ri-git-branch-line"></i>
                                            </div>

                                            <div class="flex-grow-1 min-w-0">

                                                <div class="hierarchy-label">
                                                    Sub Kegiatan
                                                </div>

                                                <div class="hierarchy-title">

                                                    {{-- DATA DINAMIS --}}
                                                    Pengembangan Fitur Laporan

                                                </div>

                                                <div class="hierarchy-meta">

                                                    {{-- DATA DINAMIS --}}
                                                    ID: SK-007
                                                    <span class="mx-1">•</span>
                                                    1 indikator sub kegiatan

                                                </div>

                                            </div>

                                        </div>

                                    </button>

                                </div>


                                {{-- =================================================
                        INDIKATOR SUB KEGIATAN
                        ================================================= --}}
                                <div class="hierarchy-item">

                                    <div class="hierarchy-connector"></div>

                                    <button type="button" class="hierarchy-card selected">

                                        <div class="d-flex align-items-start gap-3">

                                            <div class="hierarchy-icon bg-soft-primary text-primary flex-shrink-0">
                                                <i class="ri-bar-chart-box-line"></i>
                                            </div>

                                            <div class="flex-grow-1 min-w-0">

                                                <div class="d-flex justify-content-between gap-2">

                                                    <span class="hierarchy-label">
                                                        Indikator Sub Kegiatan
                                                    </span>

                                                    <span class="badge bg-primary">
                                                        Dipilih
                                                    </span>

                                                </div>

                                                <div class="hierarchy-title">

                                                    {{-- DATA DINAMIS --}}
                                                    Tingkat Penyelesaian Pengembangan Fitur Laporan

                                                </div>

                                                <div class="hierarchy-meta">

                                                    {{-- DATA DINAMIS --}}
                                                    ID: ISK-009
                                                    <span class="mx-1">•</span>
                                                    Target: 100%
                                                    <span class="mx-1">•</span>
                                                    Efektivitas

                                                </div>

                                            </div>

                                        </div>

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
            RIGHT : DETAIL
            ===================================================== --}}
                    <div class="col-xl-8 col-lg-7">

                        <div class="card">

                            {{-- =================================================
                    DETAIL HEADER
                    ================================================= --}}
                            <div class="card-body border-bottom">

                                <div class="d-flex align-items-start justify-content-between gap-3">

                                    <div class="d-flex align-items-start gap-3 min-w-0">

                                        <div class="avatar-sm flex-shrink-0">

                                            <span class="avatar-title rounded bg-soft-primary text-primary fs-20">

                                                <i class="ri-bar-chart-box-line"></i>

                                            </span>

                                        </div>

                                        <div class="min-w-0">

                                            <span class="text-primary fw-medium fs-12 text-uppercase">
                                                Indikator Sub Kegiatan
                                            </span>

                                            <h4 class="mb-1 mt-1 text-truncate">

                                                {{-- DATA DINAMIS --}}
                                                Tingkat Penyelesaian Pengembangan Fitur Laporan

                                            </h4>

                                            <p class="text-muted mb-0">

                                                {{-- DATA DINAMIS --}}
                                                Indikator kinerja pada sub kegiatan Pengembangan Fitur Laporan.

                                            </p>

                                        </div>

                                    </div>


                                    <div class="d-flex gap-2 flex-shrink-0">

                                        <button type="button" class="btn btn-soft-primary btn-sm">

                                            <i class="ri-edit-line me-1"></i>
                                            Edit

                                        </button>

                                        <button type="button" class="btn btn-soft-secondary btn-sm">

                                            <i class="ri-more-2-fill"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                    SUMMARY
                    ================================================= --}}
                            <div class="card-body border-bottom">

                                <div class="row g-3">

                                    <div class="col-sm-6 col-xl-3">

                                        <div class="border rounded p-3 h-100">

                                            <div class="d-flex align-items-center gap-2 mb-2">

                                                <i class="ri-focus-3-line text-primary"></i>

                                                <span class="text-muted fs-13">
                                                    Target
                                                </span>

                                            </div>

                                            <h5 class="mb-0">

                                                {{-- DATA DINAMIS --}}
                                                100%

                                            </h5>

                                        </div>

                                    </div>


                                    <div class="col-sm-6 col-xl-3">

                                        <div class="border rounded p-3 h-100">

                                            <div class="d-flex align-items-center gap-2 mb-2">

                                                <i class="ri-price-tag-3-line text-success"></i>

                                                <span class="text-muted fs-13">
                                                    Aspek
                                                </span>

                                            </div>

                                            <h5 class="mb-0">

                                                {{-- DATA DINAMIS --}}
                                                Efektivitas

                                            </h5>

                                        </div>

                                    </div>


                                    <div class="col-sm-6 col-xl-3">

                                        <div class="border rounded p-3 h-100">

                                            <div class="d-flex align-items-center gap-2 mb-2">

                                                <i class="ri-calendar-line text-warning"></i>

                                                <span class="text-muted fs-13">
                                                    Periode
                                                </span>

                                            </div>

                                            <h5 class="mb-0">

                                                {{-- DATA DINAMIS --}}
                                                Triwulan

                                            </h5>

                                        </div>

                                    </div>


                                    <div class="col-sm-6 col-xl-3">

                                        <div class="border rounded p-3 h-100">

                                            <div class="d-flex align-items-center gap-2 mb-2">

                                                <i class="ri-time-line text-danger"></i>

                                                <span class="text-muted fs-13">
                                                    Due Date
                                                </span>

                                            </div>

                                            <h5 class="mb-0">

                                                {{-- DATA DINAMIS --}}
                                                31 Des 2026

                                            </h5>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                    MINI HIERARCHY PATH
                    ================================================= --}}
                            <div class="card-body border-bottom">

                                <div class="d-flex align-items-center gap-2 overflow-auto pb-1">

                                    {{-- SASARAN --}}
                                    <button class="btn btn-soft-primary btn-sm text-nowrap">
                                        <i class="ri-focus-3-line me-1"></i>
                                        Sasaran Strategis
                                    </button>

                                    <i class="ri-arrow-right-s-line text-muted flex-shrink-0"></i>


                                    {{-- INISIATIF --}}
                                    <button class="btn btn-soft-success btn-sm text-nowrap">
                                        <i class="ri-lightbulb-line me-1"></i>
                                        Sasaran Inisiatif
                                    </button>

                                    <i class="ri-arrow-right-s-line text-muted flex-shrink-0"></i>


                                    {{-- PROGRAM --}}
                                    <button class="btn btn-soft-warning btn-sm text-nowrap">
                                        <i class="ri-file-list-3-line me-1"></i>
                                        Program
                                    </button>

                                    <i class="ri-arrow-right-s-line text-muted flex-shrink-0"></i>


                                    {{-- KEGIATAN --}}
                                    <button class="btn btn-soft-danger btn-sm text-nowrap">
                                        <i class="ri-settings-3-line me-1"></i>
                                        Kegiatan
                                    </button>

                                    <i class="ri-arrow-right-s-line text-muted flex-shrink-0"></i>


                                    {{-- SUB KEGIATAN --}}
                                    <button class="btn btn-soft-info btn-sm text-nowrap">
                                        <i class="ri-git-branch-line me-1"></i>
                                        Sub Kegiatan
                                    </button>

                                    <i class="ri-arrow-right-s-line text-muted flex-shrink-0"></i>


                                    {{-- CURRENT --}}
                                    <button class="btn btn-primary btn-sm text-nowrap">
                                        <i class="ri-bar-chart-box-line me-1"></i>
                                        Indikator
                                    </button>

                                </div>

                            </div>


                            {{-- =================================================
                    TABS
                    ================================================= --}}
                            <div class="card-body pb-0">

                                <ul class="nav nav-tabs nav-tabs-custom nav-primary">

                                    <li class="nav-item">

                                        <a href="#detail" data-bs-toggle="tab" class="nav-link active">

                                            <i class="ri-information-line me-1"></i>
                                            Detail

                                        </a>

                                    </li>

                                    <li class="nav-item">

                                        <a href="#monitoring" data-bs-toggle="tab" class="nav-link">

                                            <i class="ri-line-chart-line me-1"></i>
                                            Monitoring

                                        </a>

                                    </li>

                                    <li class="nav-item">

                                        <a href="#files" data-bs-toggle="tab" class="nav-link">

                                            <i class="ri-file-list-3-line me-1"></i>
                                            File Pelaporan

                                        </a>

                                    </li>

                                </ul>

                            </div>


                            <div class="card-body">

                                <div class="tab-content">


                                    {{-- =================================================
                            TAB DETAIL
                            ================================================= --}}
                                    <div class="tab-pane fade show active" id="detail">

                                        <div class="row g-3">


                                            {{-- INFORMASI UMUM --}}
                                            <div class="col-xl-7">

                                                <div class="card border shadow-none mb-0">

                                                    <div class="card-header bg-transparent">

                                                        <h6 class="card-title mb-0">

                                                            <i class="ri-information-line text-primary me-1"></i>

                                                            Informasi Umum

                                                        </h6>

                                                    </div>

                                                    <div class="card-body">

                                                        <div class="table-responsive">

                                                            <table class="table table-borderless table-sm mb-0">

                                                                <tbody>

                                                                    <tr>

                                                                        <td class="text-muted" width="35%">
                                                                            Nama Indikator
                                                                        </td>

                                                                        <td class="fw-medium">

                                                                            {{-- DATA DINAMIS --}}
                                                                            Tingkat Penyelesaian Pengembangan Fitur Laporan

                                                                        </td>

                                                                    </tr>

                                                                    <tr>

                                                                        <td class="text-muted">
                                                                            Kode
                                                                        </td>

                                                                        <td class="fw-medium">

                                                                            {{-- DATA DINAMIS --}}
                                                                            ISK-009

                                                                        </td>

                                                                    </tr>

                                                                    <tr>

                                                                        <td class="text-muted">
                                                                            Target
                                                                        </td>

                                                                        <td class="fw-medium">

                                                                            {{-- DATA DINAMIS --}}
                                                                            100%

                                                                        </td>

                                                                    </tr>

                                                                    <tr>

                                                                        <td class="text-muted">
                                                                            Aspek
                                                                        </td>

                                                                        <td class="fw-medium">

                                                                            {{-- DATA DINAMIS --}}
                                                                            Efektivitas

                                                                        </td>

                                                                    </tr>

                                                                    <tr>

                                                                        <td class="text-muted">
                                                                            Periode Pengukuran
                                                                        </td>

                                                                        <td class="fw-medium">

                                                                            {{-- DATA DINAMIS --}}
                                                                            Triwulan

                                                                        </td>

                                                                    </tr>

                                                                    <tr>

                                                                        <td class="text-muted">
                                                                            Due Date
                                                                        </td>

                                                                        <td class="fw-medium">

                                                                            {{-- DATA DINAMIS --}}
                                                                            31 Desember 2026

                                                                        </td>

                                                                    </tr>

                                                                </tbody>

                                                            </table>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- PIC --}}
                                            <div class="col-xl-5">

                                                <div class="card border shadow-none mb-0">

                                                    <div class="card-header bg-transparent">

                                                        <h6 class="card-title mb-0">

                                                            <i class="ri-user-line text-primary me-1"></i>

                                                            PIC

                                                        </h6>

                                                    </div>

                                                    <div class="card-body">

                                                        <div class="d-flex align-items-center gap-3">

                                                            <div class="avatar-sm">

                                                                <span
                                                                    class="avatar-title rounded-circle bg-soft-primary text-primary">

                                                                    {{-- DATA DINAMIS: inisial PIC --}}

                                                                    AW

                                                                </span>

                                                            </div>

                                                            <div>

                                                                <h6 class="mb-1">

                                                                    {{-- DATA DINAMIS --}}
                                                                    Ari Wibowo

                                                                </h6>

                                                                <p class="text-muted mb-0 fs-13">

                                                                    {{-- DATA DINAMIS --}}
                                                                    Unit Teknologi Informasi

                                                                </p>

                                                            </div>

                                                        </div>

                                                        <hr>

                                                        <div class="fs-13">

                                                            <span class="text-muted">
                                                                Email:
                                                            </span>

                                                            <span class="fw-medium">

                                                                {{-- DATA DINAMIS --}}
                                                                ari.wibowo@rsjantung.go.id

                                                            </span>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- UPAYA --}}
                                            <div class="col-12">

                                                <div class="card border shadow-none mb-0">

                                                    <div class="card-header bg-transparent">

                                                        <h6 class="card-title mb-0">

                                                            <i class="ri-list-check-2 text-primary me-1"></i>

                                                            Upaya

                                                        </h6>

                                                    </div>

                                                    <div class="card-body">

                                                        <ol class="mb-0 ps-3">

                                                            {{-- DATA DINAMIS: foreach upaya --}}

                                                            <li class="mb-2">
                                                                Pengembangan modul laporan sesuai kebutuhan pengguna.
                                                            </li>

                                                            <li class="mb-2">
                                                                Koordinasi dengan tim terkait untuk validasi data.
                                                            </li>

                                                            <li>
                                                                Melakukan uji coba dan perbaikan sistem.
                                                            </li>

                                                        </ol>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- KETERANGAN --}}
                                            <div class="col-md-6">

                                                <div class="card border shadow-none h-100 mb-0">

                                                    <div class="card-header bg-transparent">

                                                        <h6 class="card-title mb-0">

                                                            <i class="ri-chat-3-line text-primary me-1"></i>

                                                            Keterangan

                                                        </h6>

                                                    </div>

                                                    <div class="card-body">

                                                        <p class="text-muted mb-0">

                                                            {{-- DATA DINAMIS --}}
                                                            Pengembangan fitur laporan masih dalam tahap pengujian dan
                                                            perbaikan minor.

                                                        </p>

                                                    </div>

                                                </div>

                                            </div>


                                            {{-- IDENTIFIKASI --}}
                                            <div class="col-md-6">

                                                <div class="card border shadow-none h-100 mb-0">

                                                    <div class="card-header bg-transparent">

                                                        <h6 class="card-title mb-0">

                                                            <i class="ri-search-eye-line text-primary me-1"></i>

                                                            Identifikasi

                                                        </h6>

                                                    </div>

                                                    <div class="card-body">

                                                        <ul class="mb-0 ps-3 text-muted">

                                                            {{-- DATA DINAMIS --}}

                                                            <li class="mb-2">
                                                                Kebutuhan pengguna terhadap format laporan tambahan.
                                                            </li>

                                                            <li>
                                                                Integrasi dengan sistem eksternal.
                                                            </li>

                                                        </ul>

                                                    </div>

                                                </div>

                                            </div>


                                        </div>

                                    </div>


                                    {{-- =================================================
                            TAB MONITORING
                            ================================================= --}}
                                    <div class="tab-pane fade" id="monitoring">

                                        <div
                                            class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">

                                            <div>

                                                <h5 class="fs-15 mb-1">
                                                    Monitoring Kinerja
                                                </h5>

                                                <p class="text-muted mb-0">
                                                    Capaian indikator berdasarkan periode triwulan.
                                                </p>

                                            </div>

                                            <button type="button" class="btn btn-primary btn-sm">

                                                <i class="ri-edit-line me-1"></i>
                                                Update Monitoring

                                            </button>

                                        </div>


                                        <div class="table-responsive">

                                            <table class="table table-hover align-middle mb-0">

                                                <thead class="table-light">

                                                    <tr>

                                                        <th>Periode</th>
                                                        <th>Target</th>
                                                        <th>Capaian</th>
                                                        <th>Status</th>
                                                        <th>Last Update</th>
                                                        <th width="80">Aksi</th>

                                                    </tr>

                                                </thead>

                                                <tbody>

                                                    {{-- DATA DINAMIS: foreach periode monitoring --}}

                                                    <tr>

                                                        <td class="fw-medium">
                                                            TW I
                                                        </td>

                                                        <td>
                                                            100%
                                                        </td>

                                                        <td>
                                                            60%
                                                        </td>

                                                        <td>

                                                            <span class="badge bg-soft-warning text-warning">
                                                                Sedang Berlangsung
                                                            </span>

                                                        </td>

                                                        <td>
                                                            <span class="text-muted">
                                                                12 Apr 2026
                                                            </span>
                                                        </td>

                                                        <td>

                                                            <button type="button" class="btn btn-sm btn-soft-primary">

                                                                <i class="ri-edit-line"></i>

                                                            </button>

                                                        </td>

                                                    </tr>


                                                    <tr>

                                                        <td class="fw-medium">
                                                            TW II
                                                        </td>

                                                        <td>
                                                            100%
                                                        </td>

                                                        <td>
                                                            30%
                                                        </td>

                                                        <td>

                                                            <span class="badge bg-soft-secondary text-secondary">
                                                                Belum Diisi
                                                            </span>

                                                        </td>

                                                        <td>
                                                            -
                                                        </td>

                                                        <td>

                                                            <button type="button" class="btn btn-sm btn-soft-primary">

                                                                <i class="ri-edit-line"></i>

                                                            </button>

                                                        </td>

                                                    </tr>


                                                    <tr>

                                                        <td class="fw-medium">
                                                            TW III
                                                        </td>

                                                        <td>
                                                            100%
                                                        </td>

                                                        <td>
                                                            0%
                                                        </td>

                                                        <td>

                                                            <span class="badge bg-soft-secondary text-secondary">
                                                                Belum Diisi
                                                            </span>

                                                        </td>

                                                        <td>
                                                            -
                                                        </td>

                                                        <td>

                                                            <button type="button" class="btn btn-sm btn-soft-primary">

                                                                <i class="ri-edit-line"></i>

                                                            </button>

                                                        </td>

                                                    </tr>


                                                    <tr>

                                                        <td class="fw-medium">
                                                            TW IV
                                                        </td>

                                                        <td>
                                                            100%
                                                        </td>

                                                        <td>
                                                            0%
                                                        </td>

                                                        <td>

                                                            <span class="badge bg-soft-secondary text-secondary">
                                                                Belum Diisi
                                                            </span>

                                                        </td>

                                                        <td>
                                                            -
                                                        </td>

                                                        <td>

                                                            <button type="button" class="btn btn-sm btn-soft-primary">

                                                                <i class="ri-edit-line"></i>

                                                            </button>

                                                        </td>

                                                    </tr>

                                                </tbody>

                                            </table>

                                        </div>

                                    </div>


                                    {{-- =================================================
                            TAB FILE PELAPORAN
                            ================================================= --}}
                                    <div class="tab-pane fade" id="files">

                                        <div
                                            class="d-flex flex-wrap align-items-center justify-content-between gap-2 mb-3">

                                            <div>

                                                <h5 class="fs-15 mb-1">
                                                    File Pelaporan
                                                </h5>

                                                <p class="text-muted mb-0">
                                                    Dokumen pendukung indikator.
                                                </p>

                                            </div>

                                            <button type="button" class="btn btn-primary btn-sm">

                                                <i class="ri-upload-2-line me-1"></i>
                                                Upload File

                                            </button>

                                        </div>


                                        <div class="row g-3">

                                            {{-- DATA DINAMIS: foreach file pelaporan --}}

                                            <div class="col-md-6">

                                                <div class="border rounded p-3">

                                                    <div class="d-flex align-items-center gap-3">

                                                        <div class="avatar-sm flex-shrink-0">

                                                            <span class="avatar-title rounded bg-soft-danger text-danger">

                                                                <i class="ri-file-pdf-2-line fs-20"></i>

                                                            </span>

                                                        </div>

                                                        <div class="flex-grow-1 min-w-0">

                                                            <h6 class="mb-1 text-truncate">

                                                                Rancangan Modul.pdf

                                                            </h6>

                                                            <p class="text-muted mb-0 fs-12">

                                                                2.4 MB
                                                                <span class="mx-1">•</span>
                                                                12 Apr 2026

                                                            </p>

                                                        </div>

                                                        <button type="button" class="btn btn-sm btn-soft-primary">

                                                            <i class="ri-eye-line"></i>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>


                                            <div class="col-md-6">

                                                <div class="border rounded p-3">

                                                    <div class="d-flex align-items-center gap-3">

                                                        <div class="avatar-sm flex-shrink-0">

                                                            <span class="avatar-title rounded bg-soft-info text-info">

                                                                <i class="ri-file-word-2-line fs-20"></i>

                                                            </span>

                                                        </div>

                                                        <div class="flex-grow-1 min-w-0">

                                                            <h6 class="mb-1 text-truncate">

                                                                Dokumentasi Pengujian.docx

                                                            </h6>

                                                            <p class="text-muted mb-0 fs-12">

                                                                1.2 MB
                                                                <span class="mx-1">•</span>
                                                                15 Apr 2026

                                                            </p>

                                                        </div>

                                                        <button type="button" class="btn btn-sm btn-soft-primary">

                                                            <i class="ri-eye-line"></i>

                                                        </button>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                            </div>


                            {{-- =================================================
                    FOOTER ACTION
                    ================================================= --}}
                            <div class="card-footer bg-transparent">

                                <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">

                                    <button type="button" class="btn btn-soft-secondary"
                                        onclick="window.history.back()">

                                        <i class="ri-arrow-left-line me-1"></i>
                                        Kembali ke Daftar

                                    </button>


                                    <button type="button" class="btn btn-primary">

                                        <i class="ri-node-tree me-1"></i>
                                        Lihat Struktur

                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>
    </div>


    {{-- =============================================================
    CUSTOM STYLE
    Hanya untuk hierarchy.
    Selebihnya menggunakan Bootstrap + Minia.
    ============================================================= --}}
    @push('styles')
        <style>
            /*
            |--------------------------------------------------------------------------
            | Hierarchy
            |--------------------------------------------------------------------------
            */

            .hierarchy-item {
                position: relative;
            }

            .hierarchy-item:not(.hierarchy-year) {
                padding-top: 10px;
            }

            .hierarchy-connector {
                position: absolute;
                left: 19px;
                top: 0;
                bottom: -10px;
                width: 1px;
                background-color: var(--vz-border-color);
            }

            .hierarchy-line {
                position: relative;
                display: flex;
                align-items: flex-start;
                gap: 12px;
            }

            .hierarchy-icon {
                width: 40px;
                height: 40px;
                min-width: 40px;

                display: flex;
                align-items: center;
                justify-content: center;

                border-radius: 8px;

                font-size: 18px;

                position: relative;
                z-index: 2;
            }

            .hierarchy-content {
                min-width: 0;
                padding-top: 1px;
            }

            .hierarchy-label {
                display: block;

                color: var(--vz-secondary-color);

                font-size: 11px;
                font-weight: 600;

                text-transform: uppercase;
                letter-spacing: .3px;

                margin-bottom: 3px;
            }

            .hierarchy-title {
                display: block;

                color: var(--vz-heading-color);

                font-size: 14px;
                font-weight: 600;

                line-height: 1.4;

                word-break: break-word;
            }

            .hierarchy-meta {
                color: var(--vz-secondary-color);

                font-size: 11px;

                margin-top: 4px;

                line-height: 1.5;
            }

            .hierarchy-card {
                width: 100%;

                position: relative;
                z-index: 1;

                border: 1px solid var(--vz-border-color);

                background-color: var(--vz-secondary-bg);

                border-radius: 8px;

                padding: 12px;

                text-align: left;

                transition: all .2s ease;
            }

            .hierarchy-card:hover {
                border-color: var(--vz-primary);

                background-color: var(--vz-tertiary-bg);

                transform: translateX(2px);
            }

            .hierarchy-card.active {
                border-color: rgba(var(--vz-primary-rgb), .35);

                background-color: rgba(var(--vz-primary-rgb), .04);
            }

            .hierarchy-card.selected {
                border-color: var(--vz-primary);

                background-color: rgba(var(--vz-primary-rgb), .07);

                box-shadow: 0 0 0 1px rgba(var(--vz-primary-rgb), .08);
            }


            /*
            |--------------------------------------------------------------------------
            | Responsive
            |--------------------------------------------------------------------------
            */

            @media (max-width: 991.98px) {

                .hierarchy-card {
                    padding: 10px;
                }

                .hierarchy-title {
                    font-size: 13px;
                }

            }


            @media (max-width: 575.98px) {

                .page-title-box {
                    display: block !important;
                }

                .page-title-right {
                    margin-top: 10px;
                }

                .page-title-right .breadcrumb {
                    white-space: nowrap;
                    overflow-x: auto;
                    flex-wrap: nowrap;
                }

                .hierarchy-icon {
                    width: 36px;
                    height: 36px;
                    min-width: 36px;
                }

                .hierarchy-meta {
                    font-size: 10px;
                }

                .hierarchy-card {
                    padding: 10px;
                }

                /*
                Memastikan tombol aksi di header
                tidak menyebabkan overflow.
                */

                .card-body>.d-flex {
                    flex-wrap: wrap;
                }

            }
        </style>
    @endpush

@endsection
