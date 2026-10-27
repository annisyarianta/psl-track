@extends('layouts.app')

@section('title')
    Form Monitoring | PSL Track
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">Isi Monitoring</h3>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item"><a href="{{ route('monitoring.index') }}">Monitoring</a></li>
                                    <li class="breadcrumb-item active">Form Monitoring</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title mb-3">Informasi Indikator</h4>
                        <div class="row">
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Sub Kegiatan</p>
                                <h6>
                                    {{ $indikatorSubKegiatan->subKegiatan->judul_sub_kegiatan }}
                                </h6>
                            </div>
                            <div class="col-md-4">
                                <p class="text-muted mb-1">Indikator Sub Kegiatan</p>
                                <h6>{{ $indikatorSubKegiatan->nama_indikator_sub_kegiatan }}</h6>
                            </div>
                            <div class="col-md-2">
                                <p class="text-muted mb-1">Periode</p>
                                <h6>TW {{ $periodeTriwulan->triwulan }} &middot; {{ $tahunPeriode->tahun }}</h6>
                            </div>
                            <div class="col-md-2">
                                <p class="text-muted mb-1">Target</p>
                                <h6>{{ $indikatorSubKegiatan->target_staff ?? '-' }}</h6>
                            </div>
                            <div class="col-md-6 mt-3">
                                <p class="text-muted mb-1">Unit</p>
                                <h6>{{ $daftarNamaPicUnit->isNotEmpty() ? $daftarNamaPicUnit->implode(', ') : '-' }}</h6>
                            </div>
                            <div class="col-md-6 mt-3">
                                <p class="text-muted mb-1">PIC</p>
                                <h6>{{ $daftarNamaPicStaff->isNotEmpty() ? $daftarNamaPicStaff->implode(', ') : '-' }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('monitoring.update', $monitoring) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="upaya" class="form-label">Upaya</label>
                                    <textarea id="upaya" name="upaya" rows="3" class="form-control @error('upaya') is-invalid @enderror">{{ old('upaya', $monitoring->upaya) }}</textarea>
                                    @error('upaya')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="capaian" class="form-label">Capaian</label>
                                    <textarea id="capaian" name="capaian" rows="3" class="form-control @error('capaian') is-invalid @enderror">{{ old('capaian', $monitoring->capaian) }}</textarea>
                                    @error('capaian')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="keterangan" class="form-label">Keterangan</label>
                                    <textarea id="keterangan" name="keterangan" rows="3" class="form-control">{{ old('keterangan', $monitoring->keterangan) }}</textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="identifikasi" class="form-label">Identifikasi</label>
                                    <textarea id="identifikasi" name="identifikasi" rows="3" class="form-control">{{ old('identifikasi', $monitoring->identifikasi) }}</textarea>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label d-block">Status</label>
                                @foreach (['notstarted' => 'Belum Mulai', 'onprogress' => 'Sedang Berlangsung', 'done' => 'Selesai'] as $nilaiStatus => $labelStatus)
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="status"
                                            id="status-{{ $nilaiStatus }}" value="{{ $nilaiStatus }}"
                                            @checked(old('status', $monitoring->status ?? 'notstarted') === $nilaiStatus)>
                                        <label class="form-check-label"
                                            for="status-{{ $nilaiStatus }}">{{ $labelStatus }}</label>
                                    </div>
                                @endforeach
                                @error('status')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="filePelaporan" class="form-label">File pelaporan</label>
                                <input type="file" id="filePelaporan" name="file_pelaporan[]" class="form-control"
                                    multiple>
                                <ul id="daftarFileBaru" class="list-group mt-2"></ul>

                                @if ($monitoring->files->isNotEmpty())
                                    <ul class="list-group mt-2">
                                        @foreach ($monitoring->files as $filePelaporan)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <a href="{{ asset('storage/' . $filePelaporan->path_file) }}"
                                                    target="_blank">{{ $filePelaporan->nama_file }}</a>
                                                <div class="form-check mb-0">
                                                    <input class="form-check-input" type="checkbox" name="hapus_file[]"
                                                        id="hapusFile{{ $filePelaporan->id_file }}"
                                                        value="{{ $filePelaporan->id_file }}">
                                                    <label class="form-check-label"
                                                        for="hapusFile{{ $filePelaporan->id_file }}">Hapus</label>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </div>
                        </div>

                        <div class="card-footer d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                @if (filled($monitoring->capaian))
                                    Terakhir diubah {{ $monitoring->last_updated_at->locale('id')->translatedFormat('d M Y') }} oleh
                                    {{ $monitoring->lastUpdatedBy->nama }}
                                @endif
                            </small>
                            <div>
                                <a href="{{ route('monitoring.index', ['id_tahun' => $periodeTriwulan->id_tahun, 'id_periode_tw' => $periodeTriwulan->id_periode_tw]) }}"
                                    class="btn btn-light">Batal</a>
                                <button type="submit" class="btn btn-primary ms-2">Simpan</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const inputFilePelaporan = document.getElementById('filePelaporan');
            const daftarFileBaru = document.getElementById('daftarFileBaru');
            const penampungFileTerpilih = new DataTransfer();

            function tampilkanDaftarFileBaru() {
                daftarFileBaru.innerHTML = '';

                Array.from(penampungFileTerpilih.files).forEach(function (file, urutanFile) {
                    const itemFile = document.createElement('li');
                    itemFile.className = 'list-group-item d-flex justify-content-between align-items-center';

                    const namaFile = document.createElement('span');
                    namaFile.textContent = file.name;

                    const tombolBatalkanFile = document.createElement('button');
                    tombolBatalkanFile.type = 'button';
                    tombolBatalkanFile.className = 'btn btn-sm btn-outline-danger';
                    tombolBatalkanFile.textContent = 'Batalkan';
                    tombolBatalkanFile.addEventListener('click', function () {
                        penampungFileTerpilih.items.remove(urutanFile);
                        inputFilePelaporan.files = penampungFileTerpilih.files;
                        tampilkanDaftarFileBaru();
                    });

                    itemFile.appendChild(namaFile);
                    itemFile.appendChild(tombolBatalkanFile);
                    daftarFileBaru.appendChild(itemFile);
                });
            }

            inputFilePelaporan.addEventListener('change', function () {
                Array.from(inputFilePelaporan.files).forEach(function (file) {
                    const sudahDipilih = Array.from(penampungFileTerpilih.files).some(function (fileTerpilih) {
                        return fileTerpilih.name === file.name
                            && fileTerpilih.size === file.size
                            && fileTerpilih.lastModified === file.lastModified;
                    });

                    if (!sudahDipilih) {
                        penampungFileTerpilih.items.add(file);
                    }
                });

                inputFilePelaporan.files = penampungFileTerpilih.files;
                tampilkanDaftarFileBaru();
            });
        });
    </script>
@endsection
