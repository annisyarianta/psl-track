@extends('layouts.app')

@section('title')
    Monitoring KPI | PSL Track
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
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">
                                Monitoring KPI
                            </h3>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('kpi.index') }}">
                                            KPI Tracker
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        KPI {{ $kpi->tahun }}
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Monitoring
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card bg-sublte-info border-info">
                            <div class="card-body">
                                {{-- HEADER --}}
                                <div class="d-flex align-items-center justify-content-between mb-4">
                                    <div>
                                        <h4 class="card-title mb-1">
                                            PROGRESS KPI {{ $kpi->tahun }}
                                        </h4>
                                        <p class="mb-0 text-muted">
                                            Detail monitoring indikator program
                                        </p>
                                    </div>
                                    <div>
                                        <a href="{{ route('indikator-program.edit', $indikatorProgram->id_indikator) }}"
                                            class="btn btn-primary btn-sm">
                                            <i class="bx bx-edit-alt me-1"></i>
                                            Edit
                                        </a>
                                    </div>
                                </div>
                                <div class="row">
                                    {{-- SASARAN --}}
                                    <div class="col-12 mb-1">
                                        <div class="d-flex">
                                            <div class="fw-medium" style="width: 100px;">
                                                Sasaran
                                            </div>
                                            <div class="flex-grow-1">
                                                :
                                                {{ $sasaranProgram->nama_sasaran ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- PROGRAM --}}
                                    <div class="col-12 mb-1">
                                        <div class="d-flex">
                                            <div class="fw-medium" style="width: 100px;">
                                                Program
                                            </div>
                                            <div class="flex-grow-1">
                                                :
                                                {{ $program->nama_program ?? '-' }}
                                            </div>
                                        </div>
                                    </div>
                                    {{-- INDIKATOR --}}
                                    <div class="col-12 mb-1">
                                        <div class="d-flex">
                                            <div class="fw-medium" style="width: 100px;">
                                                Indikator
                                            </div>
                                            <div class="flex-grow-1">
                                                :
                                                {{ $indikatorProgram->nama_indikator }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <hr class="my-4">
                                {{-- META INFORMATION --}}
                                <div class="row">
                                    {{-- TARGET --}}
                                    <div class="col-md-3 mb-3">
                                        <div class="text-muted font-size-13 mb-1">
                                            Target
                                        </div>
                                        <div class="fw-medium">
                                            {{ $indikatorProgram->target ?? '-' }}
                                        </div>
                                    </div>
                                    {{-- ASPEK --}}
                                    <div class="col-md-3 mb-3">
                                        <div class="text-muted font-size-13 mb-1">
                                            Aspek
                                        </div>
                                        <div class="fw-medium">
                                            {{ ucfirst($indikatorProgram->aspek ?? '-') }} </div>
                                    </div>
                                    {{-- PERIODE --}}
                                    <div class="col-md-3 mb-3">
                                        <div class="text-muted font-size-13 mb-1">
                                            Periode Pengukuran
                                        </div>
                                        <div class="fw-medium">
                                            {{ $indikatorProgram->periode_pengukuran ?? '-' }}
                                        </div>
                                    </div>
                                    {{-- PIC --}}
                                    <div class="col-md-3 mb-3">
                                        <div class="text-muted font-size-13 mb-1">
                                            PIC
                                        </div>
                                        <div class="font-size-16">
                                            @foreach ($picIndikators as $pic)
                                                @php
                                                    $namaPic = explode(' ', trim($pic->user->nama));
                                                    $namaSingkat = implode(' ', array_slice($namaPic, 0, 2));
                                                @endphp

                                                <span class="badge bg-primary">
                                                    {{ $namaSingkat }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                    {{-- UPAYA --}}
                                    <div class="col-9 mt-2">
                                        <div class="text-muted font-size-13 mb-1">
                                            Upaya
                                        </div>
                                        <div class="fw-medium">
                                            @if ($indikatorProgram->upaya)
                                                <ol class="mb-0 ps-3">
                                                    @foreach (preg_split("/\r\n|\n|\r/", $indikatorProgram->upaya) as $upaya)
                                                        @if (trim($upaya) !== '')
                                                            <li class="mb-1">
                                                                {{ trim($upaya) }}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ol>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </div>
                                    </div>
                                    {{-- DUE --}}
                                    <div class="col-3 mt-2">
                                        <div class="text-muted font-size-13 mb-1">
                                            Tenggat Waktu
                                        </div>
                                        <div class="fw-medium">
                                            {{ $indikatorProgram->due_date ? \Carbon\Carbon::parse($indikatorProgram->due_date)->format('d M Y') : '-' }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-1">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="mb-4">
                                    <h4 class="card-title mb-1">
                                        Monitoring Triwulan
                                    </h4>
                                    <p class="text-muted mb-0">
                                        Progress monitoring berdasarkan periode triwulan
                                    </p>
                                </div>
                                <div class="accordion" id="monitoringAccordion">
                                    @php
                                        $namaTriwulan = [
                                            1 => 'TW I',
                                            2 => 'TW II',
                                            3 => 'TW III',
                                            4 => 'TW IV',
                                        ];
                                    @endphp
                                    @forelse($periodeTw as $periode)
                                        @php
                                            $monitoring = $monitorings->get($periode->id_periode_tw);
                                            if (!$monitoring) {
                                                $statusLabel = 'Belum Diisi';
                                                $statusClass = 'bg-light text-muted';
                                            } elseif ($monitoring->status === 'verified') {
                                                $statusLabel = 'Verified';
                                                $statusClass = 'bg-success-subtle text-success';
                                            } elseif ($monitoring->status === 'submitted') {
                                                $statusLabel = 'Submitted';
                                                $statusClass = 'bg-warning-subtle text-warning';
                                            } else {
                                                $statusLabel = 'Draft';
                                                $statusClass = 'bg-secondary-subtle text-secondary';
                                            }

                                            $capaianList = [];
                                            if ($monitoring && $monitoring->capaian) {
                                                $capaianList = preg_split("/\r\n|\n|\r/", $monitoring->capaian);
                                                $capaianList = array_filter(
                                                    $capaianList,
                                                    fn($item) => trim($item) !== '',
                                                );
                                            }

                                            $files = $monitoring ? $monitoring->filePelaporan : collect();
                                            $collapseId = 'monitoringCollapse' . $periode->id_periode_tw;
                                            $headingId = 'monitoringHeading' . $periode->id_periode_tw;
                                        @endphp

                                        <div class="accordion-item mb-3 border rounded">
                                            {{-- HEADER --}}
                                            <h2 class="accordion-header" id="{{ $headingId }}">
                                                <button class="accordion-button collapsed shadow-none" type="button"
                                                    data-bs-toggle="collapse" data-bs-target="#{{ $collapseId }}"
                                                    aria-expanded="false" aria-controls="{{ $collapseId }}">
                                                    <div class="w-100">
                                                        <div class="d-flex align-items-center justify-content-between pe-2">
                                                            {{-- LEFT --}}
                                                            <div>
                                                                <div class="fw-semibold font-size-15">
                                                                    {{ $namaTriwulan[$periode->triwulan] ?? 'TW ' . $periode->triwulan }}
                                                                </div>
                                                                <div class="text-muted font-size-12 mt-1">
                                                                    @if ($monitoring)
                                                                        {{ count($capaianList) }}
                                                                        poin capaian
                                                                        <span class="mx-1">
                                                                            •
                                                                        </span>
                                                                        {{ $files->count() }}
                                                                        dokumen
                                                                    @else
                                                                        Belum ada data monitoring
                                                                    @endif
                                                                </div>
                                                            </div>
                                                            {{-- RIGHT --}}
                                                            <div class="text-end">
                                                                <span
                                                                    class="badge rounded-pill {{ $statusClass }} px-3 py-2">
                                                                    {{ $statusLabel }}
                                                                </span>
                                                                @if ($monitoring && $monitoring->last_updated_at)
                                                                    <div class="text-muted font-size-11 mt-1">
                                                                        Diperbarui oleh
                                                                        {{ $monitoring->updatedBy->nama ?? '-' }}
                                                                        ·
                                                                        {{ \Carbon\Carbon::parse($monitoring->last_updated_at)->format('d M Y, H:i') }}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </button>
                                            </h2>
                                            {{-- ===== ACCORDION BODY ===== --}}
                                            <div id="{{ $collapseId }}" class="accordion-collapse collapse"
                                                aria-labelledby="{{ $headingId }}"
                                                data-bs-parent="#monitoringAccordion">
                                                <div class="accordion-body">
                                                    @if (!$monitoring)
                                                        <div class="text-center py-5">
                                                            <div class="avatar-md mx-auto mb-3">
                                                                <div
                                                                    class="avatar-title bg-light text-muted rounded-circle font-size-24">
                                                                    <i class="bx bx-clipboard"></i>
                                                                </div>
                                                            </div>
                                                            <h5 class="font-size-15">
                                                                Belum Ada Data Monitoring
                                                            </h5>
                                                            <p class="text-muted mb-3">
                                                                Monitoring untuk
                                                                {{ $namaTriwulan[$periode->triwulan] ?? 'TW ' . $periode->triwulan }}
                                                                belum diinput.
                                                            </p>
                                                            <a href="{{ route('monitoring.create', [
                                                                'id_periode_tw' => $periode->id_periode_tw,
                                                                'id_indikator' => $indikatorProgram->id_indikator,
                                                            ]) }}"
                                                                class="btn btn-primary btn-sm">
                                                                <i class="bx bx-plus me-1"></i>
                                                                Tambah Monitoring
                                                            </a>
                                                        </div>
                                                    @else
                                                        <div class="mb-4">
                                                            <h6 class="font-size-14 mb-3">
                                                                <i class="bx bx-bar-chart-alt-2 text-primary me-1"></i>
                                                                Capaian
                                                            </h6>
                                                            @if (count($capaianList))
                                                                <ul class="mb-0 ps-3">
                                                                    @foreach ($capaianList as $capaian)
                                                                        <li class="mb-2">
                                                                            {{ trim($capaian) }}
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @else
                                                                <span class="text-muted">
                                                                    Belum ada capaian.
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="mb-4">
                                                            <div
                                                                class="d-flex align-items-center justify-content-between mb-3">
                                                                <h6 class="font-size-14 mb-0">
                                                                    <i class="bx bx-file text-primary me-1"></i>
                                                                    Dokumen
                                                                </h6>
                                                                <span class="text-muted font-size-12">
                                                                    {{ $files->count() }} file
                                                                </span>
                                                            </div>
                                                            @if ($files->count())
                                                                <div class="d-flex flex-wrap gap-2">
                                                                    @foreach ($files->take(3) as $file)
                                                                        <button type="button"
                                                                            class="btn btn-light border document-chip"
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#documentPreviewModal"
                                                                            data-file-url="{{ asset('storage/' . $file->path_file) }}"
                                                                            data-file-name="{{ $file->nama_file }}">
                                                                            <i class="bx bx-file me-1 text-primary"></i>
                                                                            {{ \Illuminate\Support\Str::limit($file->nama_file, 30) }}
                                                                        </button>
                                                                    @endforeach
                                                                    @if ($files->count() > 3)
                                                                        <button type="button"
                                                                            class="btn btn-primary-subtle text-primary">
                                                                            +{{ $files->count() - 3 }}
                                                                            file
                                                                        </button>
                                                                    @endif
                                                                </div>
                                                            @else
                                                                <span class="text-muted">
                                                                    Belum ada dokumen.
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="mb-4">
                                                            <h6 class="font-size-14 mb-2">
                                                                <i class="bx bx-note text-primary me-1"></i>
                                                                Keterangan
                                                            </h6>
                                                            @if ($monitoring->keterangan)
                                                                <div class="text-muted">
                                                                    {!! nl2br(e($monitoring->keterangan)) !!}
                                                                </div>
                                                            @else
                                                                <span class="text-muted">
                                                                    Tidak ada keterangan.
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="mb-4">
                                                            <h6 class="font-size-14 mb-2">
                                                                <i class="bx bx-search-alt text-primary me-1"></i>
                                                                Identifikasi
                                                            </h6>
                                                            @if ($monitoring->identifikasi)
                                                                <div class="text-muted">
                                                                    {!! nl2br(e($monitoring->identifikasi)) !!}
                                                                </div>
                                                            @else
                                                                <span class="text-muted">
                                                                    Tidak ada identifikasi.
                                                                </span>
                                                            @endif
                                                        </div>
                                                        <div class="border-top pt-3">
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <div>
                                                                    <div class="text-muted font-size-12">
                                                                        Terakhir diperbarui
                                                                    </div>
                                                                    <div class="font-size-13">
                                                                        {{ $monitoring->updatedBy->nama ?? '-' }}
                                                                        @if ($monitoring->last_updated_at)
                                                                            ·
                                                                            {{ \Carbon\Carbon::parse($monitoring->last_updated_at)->format('d M Y, H:i') }}
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                                <div>
                                                                    <a href="{{ route('monitoring.edit', $monitoring->id_monitoring) }}"
                                                                        class="btn btn-primary btn-sm">
                                                                        <i class="bx bx-edit-alt me-1"></i>
                                                                        Edit Monitoring
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-center py-5">
                                            <div class="avatar-md mx-auto mb-3">
                                                <div class="avatar-title bg-light text-muted rounded-circle font-size-24">
                                                    <i class="bx bx-calendar-x"></i>
                                                </div>
                                            </div>
                                            <h5 class="font-size-15">
                                                Periode Triwulan Belum Tersedia
                                            </h5>
                                            <p class="text-muted mb-0">
                                                Belum terdapat periode monitoring untuk KPI ini.
                                            </p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ====== DOCUMENT PREVIEW MODAL ===== --}}
            <div class="modal fade" id="documentPreviewModal" tabindex="-1" aria-labelledby="documentPreviewModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="documentPreviewModalLabel">
                                Preview Dokumen
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            </button>
                        </div>
                        <div class="modal-body p-0">
                            <div style="height: 75vh;">
                                <iframe id="documentPreviewFrame" src="" width="100%" height="100%"
                                    frameborder="0">
                                </iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const previewModal =
                document.getElementById('documentPreviewModal');
            const previewFrame =
                document.getElementById('documentPreviewFrame');
            const previewTitle =
                document.getElementById('documentPreviewModalLabel');
            document.querySelectorAll('.document-chip')
                .forEach(function(button) {
                    button.addEventListener('click', function() {
                        const fileUrl =
                            this.dataset.fileUrl;
                        const fileName =
                            this.dataset.fileName;
                        previewTitle.textContent =
                            fileName;
                        previewFrame.src =
                            fileUrl;
                    });
                });
            previewModal.addEventListener(
                'hidden.bs.modal',
                function() {
                    previewFrame.src = '';
                    previewTitle.textContent =
                        'Preview Dokumen';
                }
            );
        });
    </script>
@endsection
