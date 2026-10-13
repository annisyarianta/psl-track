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

    @if (session('error_hapus'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Swal.fire({
                    title: 'Data tidak dapat dihapus',
                    text: @json(session('error_hapus')),
                    icon: 'error',
                    confirmButtonColor: '#5156be',
                    confirmButtonText: 'OK',
                });
            });
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
                        <div class="card shadow-sm">
                            <div class="card-header border-bottom-dashed">
                                <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                    <div>
                                        <h4 class="card-title mb-1">
                                            Sasaran Strategis
                                        </h4>
                                        <p class="text-muted mb-0 fs-13">
                                            Daftar sasaran direktur utama
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
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    @forelse($sasaranStrategis ?? [] as $index => $sasaran)
                                        <div class="list-group-item list-group-item-action sasaran-item" role="button"
                                            data-id="{{ $sasaran->id_sasaran_strategis }}">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-sm flex-shrink-0 me-3">
                                                    <div
                                                        class="avatar-title rounded bg-primary text-white fw-semibold">
                                                        {{ str_pad(($sasaranStrategis->firstItem() ?? 1) + $index, 2, '0', STR_PAD_LEFT) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 overflow-hidden">
                                                    <h5 class="font-size-14 mb-1 text-truncate">
                                                        {{ $sasaran->nama_sasaran_strategis }}
                                                    </h5>
                                                    <div
                                                        class="d-flex flex-wrap align-items-center gap-2 gap-lg-3 text-muted font-size-13">
                                                        <div>
                                                            <i class="bx bx-calendar me-1 text-primary align-middle"></i>
                                                            {{ $sasaran->tahun->tahun ?? '-' }}
                                                        </div>
                                                        <div>
                                                            <i
                                                                class="bx bx-purchase-tag-alt me-1 text-success align-middle"></i>
                                                            {{ $sasaran->indikator_kpi_count ?? 0 }} Indikator KPI
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex-shrink-0 ms-3">
                                                    <div class="dropdown">
                                                        <button
                                                            class="btn btn-link font-size-16 shadow-none text-muted dropdown-toggle p-0"
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bx bx-dots-vertical-rounded"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item" href="#"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#editSasaranStrategisModal{{ $sasaran->id_sasaran_strategis }}">
                                                                    <i class="bx bx-pencil me-2"></i> Edit
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <hr class="dropdown-divider">
                                                            </li>
                                                            <li>
                                                                <form
                                                                    action="{{ route('sasaran-strategis.destroy', $sasaran->id_sasaran_strategis) }}"
                                                                    method="POST" class="delete-sasaran-strategis">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit"
                                                                        class="dropdown-item text-danger"><i
                                                                            class="bx bx-trash me-2"></i> Hapus</button>
                                                                </form>
                                                            </li>
                                                        </ul>
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
                                            <h6 class="mb-1">Belum ada Sasaran Strategis</h6>
                                            <p class="text-muted font-size-13 mb-3">
                                                Tambahkan sasaran strategis untuk mulai mengelola KPI.
                                            </p>
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#tambahSasaranStrategis" class="btn btn-primary btn-sm">
                                                Tambah Sasaran
                                            </button>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->
                    </div>

                    {{-- KANAN : INDIKATOR KPI --}}
                    <div class="col-xl-7 col-lg-7">
                        <div class="card shadow-sm h-100">
                            <div id="indikatorEmptyState"
                                class="card-body d-flex align-items-center justify-content-center">
                                <div class="text-center px-4 py-5">
                                    <div class="avatar-lg mx-auto mb-3">
                                        <div class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                            <i class="bx bx-collection bx-md"></i>
                                        </div>
                                    </div>
                                    <h5 class="mb-2">Pilih Sasaran Strategis</h5>
                                    <p class="text-muted mb-0 fs-13">
                                        Klik salah satu sasaran strategis di sebelah kiri
                                        untuk melihat indikator KPI yang terkait.
                                    </p>
                                </div>
                            </div>

                            <div id="indikatorDetail" class="d-none">
                                <div class="card-header border-bottom-dashed">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-2">
                                        <div class="min-w-0">
                                            <span class="badge bg-primary-subtle text-primary mb-2">INDIKATOR KPI</span>
                                            <h5 class="card-title mb-1" id="selectedSasaranNama"></h5>
                                            <p class="text-muted fs-13 mb-0">
                                                Daftar indikator KPI berdasarkan sasaran strategis yang dipilih.
                                            </p>
                                        </div>
                                        <button type="button" id="btnTambahIndikator"
                                            class="btn btn-primary btn-sm flex-shrink-0" data-bs-toggle="modal"
                                            data-bs-target="#tambahIndikatorKpi">
                                            <i class="bx bx-plus align-middle me-1"></i> Tambah
                                        </button>
                                    </div>
                                </div>

                                <div class="card-body p-0">
                                    <div id="indikatorList" class="list-group list-group-flush"></div>
                                    <div id="indikatorKosong" class="text-center py-5 px-3 d-none">
                                        <div class="avatar-md mx-auto mb-3">
                                            <div class="avatar-title rounded-circle bg-light text-muted">
                                                <i class="bx bx-line-chart bx-sm"></i>
                                            </div>
                                        </div>
                                        <p class="text-muted fs-13 mb-0">Belum terdapat indikator KPI pada sasaran
                                            strategis ini.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- modal tambah sasaran strategis --}}
    <div class="modal fade" id="tambahSasaranStrategis" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Tambah Sasaran Strategis
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sasaran-strategis.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="tahun" class="col-sm-3 col-form-label">Tahun
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <select class="form-select" id="tahun" name="id_tahun" required>
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
                            <label for="nama_sasaran_strategis" class="col-sm-3 col-form-label">Nama Sasaran Strategis
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="nama_sasaran_strategis" name="nama_sasaran_strategis" rows="4" required>{{ old('nama_sasaran_strategis') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
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

    {{-- modal edit sasaran strategis --}}
    <div class="modal fade" id="editSasaranStrategisModal{{ $sasaran->id_sasaran_strategis }}" data-bs-backdrop="static"
        data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Edit Sasaran Strategis
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('sasaran-strategis.update', $sasaran->id_sasaran_strategis) }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="id_tahun" value="{{ $sasaran->id_tahun }}">
                    <div class="modal-body">
                        <div class="row mb-2">
                            <label for="nama_sasaran_strategis" class="col-sm-3 col-form-label">Nama
                                Sasaran Strategis
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="nama_sasaran_strategis" name="nama_sasaran_strategis" rows="4" required>{{ old('nama_sasaran_strategis', $sasaran->nama_sasaran_strategis) }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
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

    {{-- modal tambah indikator kpi --}}
    <div class="modal fade" id="tambahIndikatorKpi" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">
                        Tambah Indikator KPI
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('indikator-kpi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_sasaran_strategis" id="inputIdSasaran"
                        value="{{ old('id_sasaran_strategis') }}">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="nama_indikator_kpi" class="col-sm-3 col-form-label">Nama Indikator KPI
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="nama_indikator_kpi" name="nama_indikator_kpi" rows="4" required>{{ old('nama_indikator_kpi') }}</textarea>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="target_dirut" class="col-sm-3 col-form-label">Target
                            </label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="target_dirut" name="target_dirut"
                                    value="{{ old('target_dirut') }}"></input>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
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

    {{-- modal edit indikator kpi --}}
    <div class="modal fade" id="editIndikatorKpiModal" data-bs-backdrop="static" data-bs-keyboard="false"
        tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Indikator KPI</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formEditIndikator" action="{{ route('indikator-kpi.update', ':id') }}" method="POST">
                    @csrf @method('PUT')
                    <input type="hidden" name="id_sasaran_strategis" id="editIdSasaran">
                    <div class="modal-body">
                        <div class="row mb-3">
                            <label for="editNamaIndikator" class="col-sm-3 col-form-label">Nama Indikator KPI
                                <span class="text-danger">*</span></label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="editNamaIndikator" name="nama_indikator_kpi" rows="4" required></textarea>
                            </div>
                        </div>
                        <div class="row mb-2">
                            <label for="editTarget" class="col-sm-3 col-form-label">Target</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="editTarget" name="target_dirut">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- form hapus indikator kpi (dikirim lewat JS + SweetAlert) --}}
    <form id="formHapusIndikator" action="{{ route('indikator-kpi.destroy', ':id') }}" method="POST" class="d-none">
        @csrf
        @method('DELETE')
    </form>

    @php
        $sasaranData = $sasaranStrategis->getCollection()->mapWithKeys(function ($s) {
            return [
                $s->id_sasaran_strategis => [
                    'nama' => $s->nama_sasaran_strategis,
                    'indikator' => $s->indikatorKpi
                        ->map(function ($i) {
                            return [
                                'id' => $i->id_indikator_kpi,
                                'nama' => $i->nama_indikator_kpi,
                                'target' => $i->target_dirut,
                                'inisiatif' => $i->sasaran_inisiatif_count ?? 0,
                            ];
                        })
                        ->values(),
                ],
            ];
        });
    @endphp

    <script>
        const sasaranData = @json($sasaranData);

        const esc = (t) => String(t ?? '').replace(/[&<>"']/g, (c) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));

        const emptyState = document.getElementById('indikatorEmptyState');
        const detail = document.getElementById('indikatorDetail');
        const list = document.getElementById('indikatorList');
        const kosong = document.getElementById('indikatorKosong');
        let idSasaranAktif = null;
        const urlUpdate = @json(route('indikator-kpi.update', ':id'));
        const urlDestroy = @json(route('indikator-kpi.destroy', ':id'));

        document.addEventListener('click', function(e) {
            if (e.target.closest('.dropdown')) return;

            const item = e.target.closest('.sasaran-item');
            if (!item) return;

            const data = sasaranData[item.dataset.id];
            if (!data) return;

            document.querySelectorAll('.sasaran-item').forEach(el => el.classList.remove('bg-primary-subtle'));
            item.classList.add('bg-primary-subtle');

            document.getElementById('selectedSasaranNama').textContent = data.nama;
            document.getElementById('inputIdSasaran').value = item.dataset.id;
            idSasaranAktif = item.dataset.id;
            list.innerHTML = data.indikator.map((i, idx) => `
            <div class="list-group-item list-group-item-action p-3">
                <div class="d-flex align-items-start gap-3 font-size-14">
                    <div class="avatar-sm flex-shrink-0">
                        <div class="avatar-title rounded bg-primary-subtle text-primary fw-semibold">
                            ${String(idx + 1).padStart(2, '0')}
                        </div>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <h6 class="mb-1 fw-semibold">${esc(i.nama)}</h6>
                        <div class="text-muted font-size-13 mb-2">
                            <span>Target :</span>
                            <span>${esc(i.target)}</span>
                        </div>
                        <span class="badge bg-light text-muted">
                            <i class="bx bx-sitemap me-1"></i>${esc(i.inisiatif)} inisiatif
                        </span>
                    </div>
                    <div class="dropdown flex-shrink-0">
                        <button class="btn btn-ghost-secondary btn-sm" type="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bx bx-dots-vertical-rounded font-size-20"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item btn-edit-indikator" href="#"
                                data-bs-toggle="modal" data-bs-target="#editIndikatorKpiModal"
                                data-id="${i.id}"
                                data-nama="${esc(i.nama)}"
                                data-target="${esc(i.target)}">
                                    <i class="bx bx-pencil me-2"></i> Edit
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <button type="button" class="dropdown-item text-danger btn-hapus-indikator"
                                        data-id="${i.id}">
                                    <i class="bx bx-trash me-2"></i> Hapus
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>`).join('');

            // ===== Indikator KPI: edit (modal) =====
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-edit-indikator');
                if (!btn) return;

                document.getElementById('formEditIndikator').action =
                    urlUpdate.replace(':id', btn.dataset.id);
                document.getElementById('editNamaIndikator').value = btn.dataset.nama;
                document.getElementById('editTarget').value = btn.dataset.target;
                document.getElementById('editIdSasaran').value = idSasaranAktif;
            });

            // ===== Hapus Indikator KPI (SweetAlert) =====
            document.addEventListener('click', function(e) {
                const btn = e.target.closest('.btn-hapus-indikator');
                if (!btn) return;

                Swal.fire({
                    title: 'Hapus data?',
                    text: 'Data yang sudah dihapus tidak dapat dikembalikan. Data yang masih memiliki data turunan tidak dapat dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2ab57d',
                    cancelButtonColor: '#fd625e',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                }).then(function(result) {
                    if (result.isConfirmed) {
                        const form = document.getElementById('formHapusIndikator');
                        form.action = urlDestroy.replace(':id', btn.dataset.id);
                        form.submit();
                    }
                });
            });

            kosong.classList.toggle('d-none', data.indikator.length > 0);
            emptyState.classList.add('d-none');
            detail.classList.remove('d-none');
        });
    </script>

    {{-- hapus sasaran strategis --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteForms = document.querySelectorAll('.delete-sasaran-strategis');
            deleteForms.forEach(function(form) {
                form.addEventListener('submit', function(event) {
                    event.preventDefault();
                    Swal.fire({
                        title: 'Hapus data?',
                        text: 'Data yang sudah dihapus tidak dapat dikembalikan. Data yang masih memiliki data turunan tidak dapat dihapus.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#2ab57d',
                        cancelButtonColor: '#fd625e',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal',
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
