@extends('layouts.app')

@section('title')
    Tambah Indikator Program | PSL Track
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <!-- start page title -->
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">
                                Tambah Indikator Program
                            </h3>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">KPI Tracker</a>
                                    </li>
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('kpi.show', $kpi->id_kpi) }}">Detail KPI</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Tambah Indikator
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end page title -->

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title mb-1">Program</h4>
                                <p class="text-muted mb-0">{{ $program->nama_program }}</p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('indikator-program.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_program" value="{{ $program->id_program }}">
                                    <div class="row mb-3">
                                        <label for="nama-indikator" class="col-sm-2 col-form-label">Nama
                                            Indikator
                                            <span class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nama-indikator"
                                                name="nama_indikator" value="{{ old('nama_indikator') }}"
                                                placeholder="Masukkan nama indikator" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="target" class="col-sm-2 col-form-label">Target
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="target" name="target"
                                                value="{{ old('target') }}" placeholder="Contoh: 60%">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="aspek" class="col-sm-2 col-form-label">Aspek</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="aspek" name="aspek">
                                                <option value="">-- Pilih Aspek --</option>
                                                @foreach ($aspekOptions as $opt)
                                                    <option value="{{ $opt }}">{{ $opt }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="periode_pengukuran" class="col-sm-2 col-form-label">Periode Pengukuran
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="periode_pengukuran"
                                                name="periode_pengukuran" value="{{ old('periode_pengukuran') }}"
                                                placeholder="Contoh: Tahunan">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="upaya" class="col-sm-2 col-form-label">Upaya</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="upaya" name="upaya" rows="3"
                                                placeholder="Masukkan upaya pencapaian indikator">{{ old('upaya') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="pic_indikator" class="col-sm-2 col-form-label">
                                            PIC
                                        </label>
                                        <div class="col-sm-10">
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
                                    <div class="row mb-4">
                                        <label for="due_date" class="col-sm-2 col-form-label">Tenggat Waktu</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="due_date" name="due_date"
                                                value="{{ old('due_date') }}">
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end">
                                                <button type="button" class="btn btn-secondary w-md"
                                                    onclick="window.location.href='{{ route('kpi.show', $kpi->id_kpi) }}'">Batal</button>
                                                <button type="submit" class="btn btn-primary w-md ms-4">Simpan</button>
                                            </div>
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
