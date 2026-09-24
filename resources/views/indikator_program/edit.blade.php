@extends('layouts.app')

@section('title')
    Edit Indikator Program | PSL Track
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
                                Edit Indikator Program
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
                                        Edit Indikator Program
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
                                <p class="text-muted mb-0">{{ $indikatorProgram->program->nama_program }}</p>
                            </div>
                            <div class="card-body">
                                <form action="{{ route('indikator-program.update', $indikatorProgram->id_indikator) }}"
                                    method="POST">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="id_program" value="{{ $indikatorProgram->id_program }}">
                                    <div class="row mb-3">
                                        <label for="nama-indikator" class="col-sm-2 col-form-label">Nama
                                            Indikator
                                            <span class="text-danger">*</span></label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="nama-indikator"
                                                name="nama_indikator"
                                                value="{{ old('nama_indikator', $indikatorProgram->nama_indikator) }}"
                                                placeholder="Masukkan nama indikator" required>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="target" class="col-sm-2 col-form-label">Target
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="target" name="target"
                                                value="{{ old('target', $indikatorProgram->target) }}"
                                                placeholder="Masukkan target">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="aspek" class="col-sm-2 col-form-label">Aspek</label>
                                        <div class="col-sm-10">
                                            <select class="form-select" id="aspek" name="aspek">
                                                <option value="kualitas"
                                                    {{ old('aspek', $indikatorProgram->aspek) == 'kualitas' ? 'selected' : '' }}>
                                                    Kualitas
                                                </option>
                                                <option value="kuantitas"
                                                    {{ old('aspek', $indikatorProgram->aspek) == 'kuantitas' ? 'selected' : '' }}>
                                                    Kuantitas
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="periode_pengukuran" class="col-sm-2 col-form-label">Periode Pengukuran
                                        </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control" id="periode_pengukuran"
                                                name="periode_pengukuran"
                                                value="{{ old('periode_pengukuran', $indikatorProgram->periode_pengukuran) }}"
                                                placeholder="Masukkan periode pengukuran">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="upaya" class="col-sm-2 col-form-label">Upaya</label>
                                        <div class="col-sm-10">
                                            <textarea class="form-control" id="upaya" name="upaya" rows="3" placeholder="Masukkan upaya">{{ old('upaya', $indikatorProgram->upaya) }}</textarea>
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
                                                        {{ in_array($user->id_user, old('id_user', $selectedPicIds ?? [])) ? 'selected' : '' }}>
                                                        {{ $user->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-4">
                                        <label for="due_date" class="col-sm-2 col-form-label">Due Date</label>
                                        <div class="col-sm-10">
                                            <input type="date" class="form-control" id="due_date" name="due_date"
                                                value="{{ old('due_date', $indikatorProgram->due_date ? \Carbon\Carbon::parse($indikatorProgram->due_date)->format('Y-m-d') : '') }}">
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
