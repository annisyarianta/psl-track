@extends('layouts.app')

@section('title')
    {{ $task ? 'Edit Task' : 'Tambah Task' }}
@endsection

@section('content')
    @php
        $idPenerimaTerpilih = old('penerima_tugas', $task ? $task->penerimaTugas->pluck('id_user')->all() : []);
    @endphp

    <style>
        /* Tampilan kolom PIC saat terkunci (penerima tugas) */
        .choices.is-disabled .choices__button {
            display: none;
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">
                                Form Task
                            </h3>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('task.index') }}">Task Tracker</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Form Task
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $pesanError)
                                <li>{{ $pesanError }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if ($task && !$apakahPemberiTugas)
                    <div class="alert alert-info">
                        Anda sebagai penerima tugas hanya dapat mengubah <strong>status</strong>,
                        <strong>hasil tugas</strong>, dan <strong>file lampiran</strong>.
                    </div>
                @endif

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <form action="{{ $task ? route('task.update', $task->id_task) : route('task.store') }}"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    @if ($task)
                                        @method('PUT')
                                    @endif

                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label">Program <span
                                                class="text-muted">(opsional)</span></label>
                                        <div class="col-sm-9">
                                            <select name="id_program" class="form-select" @disabled(!$apakahPemberiTugas)>
                                                <option value="">-- Tanpa Program (tugas independen) --</option>
                                                @foreach ($daftarProgram as $program)
                                                    <option value="{{ $program->id_program }}"
                                                        {{ (string) old('id_program', $task->id_program ?? '') === (string) $program->id_program ? 'selected' : '' }}>
                                                        {{ $program->nama_program }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label">Judul Tugas <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="text" name="judul_tugas" class="form-control" required
                                                value="{{ old('judul_tugas', $task->judul_tugas ?? '') }}"
                                                @disabled(!$apakahPemberiTugas)>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label">Deskripsi Tugas <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea name="deskripsi_tugas" rows="4" class="form-control" required @disabled(!$apakahPemberiTugas)>{{ old('deskripsi_tugas', $task->deskripsi_tugas ?? '') }}</textarea>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label">Tenggat Waktu <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <input type="date" name="tenggat_waktu" class="form-control" required
                                                value="{{ old('tenggat_waktu', optional($task?->tenggat_waktu)->format('Y-m-d')) }}"
                                                @disabled(!$apakahPemberiTugas)>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label">Status <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <div class="pt-2">
                                                @foreach ($daftarStatus as $nilaiStatus => $labelStatus)
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input" type="radio" name="status"
                                                            id="status-{{ $nilaiStatus }}" value="{{ $nilaiStatus }}"
                                                            {{ old('status', $task->status ?? 'todo') === $nilaiStatus ? 'checked' : '' }}
                                                            required>
                                                        <label class="form-check-label" for="status-{{ $nilaiStatus }}">
                                                            {{ $labelStatus }}
                                                        </label>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-4">
                                        <label class="col-sm-3 col-form-label">Ditugaskan Kepada <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <select name="penerima_tugas[]" id="penerima_tugas" class="form-control"
                                                placeholder="Tambah PIC" data-trigger multiple
                                                @disabled(!$apakahPemberiTugas)>
                                                @foreach ($daftarCalonPenerima as $calonPenerima)
                                                    <option value="{{ $calonPenerima->id_user }}"
                                                        {{ in_array($calonPenerima->id_user, $idPenerimaTerpilih) ? 'selected' : '' }}>
                                                        {{ $calonPenerima->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    @if ($task)
                                        <div class="row mb-4">
                                            <label for="hasil_tugas" class="col-sm-3 col-form-label">Hasil Tugas</label>
                                            <div class="col-sm-9">
                                                <textarea id="hasil_tugas" name="hasil_tugas" rows="4"
                                                    class="form-control @error('hasil_tugas') is-invalid @enderror"
                                                    placeholder="Tuliskan hasil pengerjaan tugas">{{ old('hasil_tugas', $task->hasil_tugas) }}</textarea>
                                                @error('hasil_tugas')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    @endif

                                    <div class="row mb-4">
                                        <label for="fileTask" class="col-sm-3 col-form-label">File Lampiran</label>
                                        <div class="col-sm-9">
                                            <input type="file" id="fileTask" name="file_task[]" class="form-control"
                                                multiple>
                                            <div class="form-text">Maksimal 10 MB per file.</div>
                                            <ul id="daftarFileBaru" class="list-group mt-2"></ul>

                                            @if ($task && $task->files->isNotEmpty())
                                                <ul class="list-group mt-2">
                                                    @foreach ($task->files as $fileTask)
                                                        @php
                                                            $bolehHapusFile =
                                                                $apakahPemberiTugas ||
                                                                (int) $fileTask->uploaded_by === $idUserLogin;
                                                        @endphp
                                                        <li
                                                            class="list-group-item d-flex justify-content-between align-items-center">
                                                            <a href="{{ asset('storage/' . $fileTask->path_file) }}"
                                                                target="_blank">{{ $fileTask->nama_file }}</a>
                                                            @if ($bolehHapusFile)
                                                                <div class="form-check mb-0">
                                                                    <input class="form-check-input" type="checkbox"
                                                                        name="hapus_file[]"
                                                                        id="hapusFile{{ $fileTask->id_file_task }}"
                                                                        value="{{ $fileTask->id_file_task }}">
                                                                    <label class="form-check-label"
                                                                        for="hapusFile{{ $fileTask->id_file_task }}">Hapus</label>
                                                                </div>
                                                            @endif
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-end">
                                                <a href="{{ route('task.index') }}" class="btn btn-light w-md">Batal</a>
                                                <button type="submit" class="btn btn-primary w-md ms-3">Simpan</button>
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

    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const apakahPemberiTugas = @json($apakahPemberiTugas);

            // Kolom penerima tugas (PIC)
            const pilihanPenerimaTugas = document.getElementById('penerima_tugas');
            const instansiChoicesPenerimaTugas = new Choices(pilihanPenerimaTugas, {
                removeItemButton: true,
                placeholderValue: pilihanPenerimaTugas.getAttribute('placeholder'),
                searchPlaceholderValue: 'Cari nama...',
                noResultsText: 'Tidak ada hasil',
                noChoicesText: 'Tidak ada pilihan lagi',
                itemSelectText: ''
            });

            if (!apakahPemberiTugas) {
                instansiChoicesPenerimaTugas.disable();
            }

            // File lampiran baru
            const inputFileTask = document.getElementById('fileTask');
            const daftarFileBaru = document.getElementById('daftarFileBaru');
            let kumpulanFileTerpilih = new DataTransfer();

            function tampilkanDaftarFileBaru() {
                daftarFileBaru.innerHTML = '';

                Array.from(kumpulanFileTerpilih.files).forEach(function(fileBaru, indeksFileBaru) {
                    const itemFileBaru = document.createElement('li');
                    itemFileBaru.className =
                        'list-group-item d-flex justify-content-between align-items-center';

                    const namaFileBaru = document.createElement('span');
                    namaFileBaru.textContent = fileBaru.name;

                    const tombolBatalkan = document.createElement('button');
                    tombolBatalkan.type = 'button';
                    tombolBatalkan.className = 'btn btn-sm btn-outline-danger ms-3 flex-shrink-0';
                    tombolBatalkan.textContent = 'Batalkan';
                    tombolBatalkan.addEventListener('click', function() {
                        const kumpulanFileSisa = new DataTransfer();

                        Array.from(kumpulanFileTerpilih.files).forEach(function(file, indeksFile) {
                            if (indeksFile !== indeksFileBaru) {
                                kumpulanFileSisa.items.add(file);
                            }
                        });

                        kumpulanFileTerpilih = kumpulanFileSisa;
                        inputFileTask.files = kumpulanFileTerpilih.files;
                        tampilkanDaftarFileBaru();
                    });

                    itemFileBaru.appendChild(namaFileBaru);
                    itemFileBaru.appendChild(tombolBatalkan);
                    daftarFileBaru.appendChild(itemFileBaru);
                });
            }

            inputFileTask.addEventListener('change', function() {
                Array.from(inputFileTask.files).forEach(function(file) {
                    kumpulanFileTerpilih.items.add(file);
                });

                inputFileTask.files = kumpulanFileTerpilih.files;
                tampilkanDaftarFileBaru();
            });
        });
    </script>
@endsection