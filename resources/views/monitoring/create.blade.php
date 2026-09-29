@extends('layouts.app')

@section('title')
    Update Monitoring | PSL Track
@endsection

@section('content')
    @if (session('success'))
        <script>
            alertify.success(@json(session('success')));
        </script>
    @endif

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">
                                Update Progress Monitoring
                            </h3>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('indikator.monitoring', $indikatorProgram->id_indikator) }}">
                                            Monitoring Indikator
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Update Monitoring
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card border shadow-none mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-md me-3 flex-shrink-0">
                                        <span
                                            class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">
                                            <i class="mdi mdi-chart-line" style="font-size: 28px;"></i>
                                        </span>
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="font-size-17 mb-1">
                                            Indikator Program
                                        </h5>
                                        <p class="text-muted mb-2 font-size-15">
                                            {{ $indikatorProgram->nama_indikator }}
                                        </p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <span class="badge bg-primary-subtle text-primary px-3 py-2"
                                                style="font-size: 14px;">
                                                <i class="mdi mdi-calendar-range me-1" style="font-size: 17px;"></i>
                                                @php
                                                    $namaTriwulan = [
                                                        1 => 'Triwulan I',
                                                        2 => 'Triwulan II',
                                                        3 => 'Triwulan III',
                                                        4 => 'Triwulan IV',
                                                    ];
                                                @endphp
                                                {{ $namaTriwulan[$periodeTw->triwulan] ?? 'TW ' . $periodeTw->triwulan }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                {{-- Main Form --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                {{-- Section Header --}}
                                <div class="d-flex align-items-center mb-4">
                                    <div class="flex-grow-1">
                                        <p class="text-muted mb-0 font-size-13">
                                            Lengkapi data progress monitoring pada periode ini.
                                        </p>
                                    </div>
                                </div>
                                <form action="{{ route('monitoring.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    {{-- Hidden ID --}}
                                    <input type="hidden" name="id_indikator" value="{{ $indikatorProgram->id_indikator }}">
                                    <input type="hidden" name="id_periode_tw" value="{{ $periodeTw->id_periode_tw }}">
                                    <div class="row mb-3">
                                        <label for="capaian" class="col-lg-2 col-form-label">
                                            Capaian
                                            <span class="text-danger">*</span>
                                        </label>
                                        <div class="col-lg-10">
                                            <textarea class="form-control @error('capaian') is-invalid @enderror" id="capaian" rows="4" name="capaian"
                                                placeholder="Masukkan capaian monitoring" required>{{ old('capaian') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="identifikasi" class="col-lg-2 col-form-label">
                                            Identifikasi
                                        </label>
                                        <div class="col-lg-10">
                                            <textarea class="form-control @error('identifikasi') is-invalid @enderror" id="identifikasi" name="identifikasi"
                                                rows="4" placeholder="Tuliskan kendala, temuan, atau hasil identifikasi...">{{ old('identifikasi') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="keterangan" class="col-lg-2 col-form-label">
                                            Keterangan
                                        </label>
                                        <div class="col-lg-10">
                                            <textarea class="form-control @error('keterangan') is-invalid @enderror" id="keterangan" name="keterangan"
                                                rows="4" placeholder="Tuliskan keterangan progress pada periode ini...">{{ old('keterangan') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="status" class="col-lg-2 col-form-label">
                                            Status
                                        </label>
                                        <div class="col-lg-10">
                                            <select class="form-control" name="status">
                                                <option value="" disabled selected>
                                                    --- Pilih status monitoring ---
                                                </option>
                                                <option value="notstarted"
                                                    {{ old('status') == 'notstarted' ? 'selected' : '' }}>
                                                    Belum Dimulai </option>
                                                <option value="onprogress"
                                                    {{ old('status') == 'onprogress' ? 'selected' : '' }}>
                                                    Sedang Berlangsung </option>
                                                <option value="done" {{ old('status') == 'done' ? 'selected' : '' }}>
                                                    Selesai </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label class="col-lg-2 col-form-label">
                                            Dokumen Pendukung
                                        </label>
                                        <div class="col-lg-10">
                                            <div id="upload-area" class="border rounded-3 p-4 text-center"
                                                style="border-style: dashed !important; cursor: pointer;">
                                                <input type="file" id="dokumen" name="dokumen[]" class="d-none"
                                                    multiple accept=".pdf,.doc,.docx,.xls,.xlsx">
                                                <div class="avatar-sm mx-auto mb-3">
                                                    <span
                                                        class="avatar-title rounded-circle bg-primary-subtle text-primary font-size-20">
                                                        <i class="mdi mdi-cloud-upload-outline"></i>
                                                    </span>
                                                </div>
                                                <h6 class="font-size-14 mb-1">
                                                    Upload Dokumen Pendukung
                                                </h6>
                                                <p class="text-muted font-size-12 mb-3">
                                                    Pilih satu atau beberapa file untuk dilampirkan
                                                </p>
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                    id="choose-file">
                                                    <i class="mdi mdi-paperclip me-1"></i>
                                                    Pilih Dokumen
                                                </button>
                                                <div class="text-muted font-size-11 mt-2">
                                                    PDF, DOC, DOCX, XLS, XLSX
                                                </div>
                                            </div>
                                            {{-- Upload Error --}}
                                            @error('dokumen')
                                                <div class="text-danger font-size-12 mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror

                                            @error('dokumen.*')
                                                <div class="text-danger font-size-12 mt-2">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                            {{-- Selected Files --}}
                                            <div id="selected-files" class="mt-3 d-none">
                                                <div class="d-flex align-items-center justify-content-between mb-2">
                                                    <h6 class="font-size-13 mb-0">
                                                        Dokumen yang dipilih
                                                    </h6>
                                                    <span id="file-count" class="badge bg-primary-subtle text-primary">
                                                        0 file
                                                    </span>
                                                </div>
                                                <div id="file-list"></div>
                                            </div>
                                        </div>
                                    </div>
                                    {{-- Action --}}
                                    <div class="d-flex flex-column flex-sm-row justify-content-end gap-2">
                                        <a href="{{ route('indikator.monitoring', $indikatorProgram->id_indikator) }}"
                                            class="btn btn-light">
                                            <i class="mdi mdi-arrow-left me-1"></i>
                                            Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="mdi mdi-content-save-outline me-1"></i>
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('dokumen');
            const uploadArea = document.getElementById('upload-area');
            const chooseFile = document.getElementById('choose-file');
            const selectedFiles = document.getElementById('selected-files');
            const fileList = document.getElementById('file-list');
            const fileCount = document.getElementById('file-count');
            let selectedFileArray = [];

            chooseFile.addEventListener('click', function(event) {
                event.stopPropagation();
                input.click();
            });

            uploadArea.addEventListener('click', function() {
                input.click();
            });

            input.addEventListener('change', function() {
                const files = Array.from(this.files);
                files.forEach(function(file) {
                    const exists = selectedFileArray.some(function(existingFile) {
                        return existingFile.name === file.name &&
                            existingFile.size === file.size;
                    });
                    if (!exists) {
                        selectedFileArray.push(file);
                    }
                });
                renderFiles();
                updateInputFiles();
            });

            function renderFiles() {
                fileList.innerHTML = '';
                if (selectedFileArray.length === 0) {
                    selectedFiles.classList.add('d-none');
                    fileCount.textContent = '0 file';
                    return;
                }
                selectedFiles.classList.remove('d-none');
                fileCount.textContent =
                    selectedFileArray.length +
                    (selectedFileArray.length === 1 ? ' file' : ' file');
                selectedFileArray.forEach(function(file, index) {
                    const fileItem = document.createElement('div');
                    fileItem.className =
                        'd-flex align-items-center justify-content-between border rounded px-3 py-2 mb-2 bg-light';
                    fileItem.innerHTML = `
                <div class="d-flex align-items-center min-w-0">
                    <div class="avatar-xs me-2 flex-shrink-0">
                        <span class="avatar-title rounded bg-primary-subtle text-primary">
                            <i class="mdi mdi-file-document-outline"></i>
                        </span>
                    </div>
                    <div class="min-w-0">
                        <div class="font-size-13 fw-medium text-truncate"
                            style="max-width: 100%;">
                            ${escapeHtml(file.name)}
                        </div>
                        <div class="text-muted font-size-11">
                            ${formatFileSize(file.size)}
                        </div>
                    </div>
                </div>
                <button type="button"
                    class="btn btn-sm btn-link text-danger p-0 ms-2 flex-shrink-0"
                    onclick="removeFile(${index})"
                    title="Hapus file">
                    <i class="mdi mdi-close font-size-18"></i>
                </button>
            `;
                    fileList.appendChild(fileItem);
                });
            }

            window.removeFile = function(index) {
                selectedFileArray.splice(index, 1);
                renderFiles();
                updateInputFiles();
            };

            function updateInputFiles() {
                const dataTransfer = new DataTransfer();
                selectedFileArray.forEach(function(file) {
                    dataTransfer.items.add(file);
                });
                input.files = dataTransfer.files;
            }

            function formatFileSize(bytes) {
                if (bytes === 0) {
                    return '0 Bytes';
                }
                const sizes = [
                    'Bytes',
                    'KB',
                    'MB',
                    'GB'
                ];
                const i = Math.floor(
                    Math.log(bytes) / Math.log(1024)
                );
                return parseFloat(
                    (bytes / Math.pow(1024, i)).toFixed(2)
                ) + ' ' + sizes[i];
            }

            function escapeHtml(value) {
                const div = document.createElement('div');
                div.textContent = value;
                return div.innerHTML;
            }
        });
    </script>
@endpush
