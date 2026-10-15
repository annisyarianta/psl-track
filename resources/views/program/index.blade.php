@extends('layouts.app')

@section('title')
    Program Manager | PSL Track
@endsection

@section('content')

    @php
        $canManage = auth()->user()->role === 'manager';
    @endphp

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
                                        KPI Tracker
                                    </li>
                                    <li class="breadcrumb-item">
                                        Dir. Utama
                                    </li>
                                    <li class="breadcrumb-item">
                                        Dir. Bagian
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Manager
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    {{-- PANEL KIRI: Indikator Inisiatif dari Dir. Bagian --}}
                    <div class="col-lg-5">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Indikator Inisiatif</h5>
                                    <p class="text-muted mb-0">Dari Dir. Bagian</p>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                @forelse ($sasaranInisiatif as $si)
                                    @if ($si->indikatorInisiatif->count())
                                        <div class="px-3 pt-3 pb-1 text-primary small">{{ $si->nama_sasaran_inisiatif }}
                                        </div>
                                        <div class="list-group list-group-flush">
                                            @foreach ($si->indikatorInisiatif as $ind)
                                                <a href="#" class="list-group-item list-group-item-action js-parent"
                                                    data-id="{{ $ind->id_indikator_inisiatif }}"
                                                    data-nama="{{ $ind->nama_indikator_inisiatif }}">
                                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                                        <div>
                                                            <h6 class="mb-1">{{ $ind->nama_indikator_inisiatif }}</h6>
                                                            <p class="text-muted small mb-0">Target :
                                                                {{ $ind->target_dirbag ?? '-' }}
                                                            </p>
                                                        </div>
                                                        <span class="badge bg-info">
                                                            {{ $ind->programs_count ?? 0 }}
                                                            Program
                                                        </span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center text-muted p-4">Belum terdapat Indikator Inisiatif dari Dir.
                                        Bagian.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- PANEL KANAN: Program milik Manager --}}
                    <div class="col-lg-7">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Program</h5>
                                    <p class="text-muted mb-0" id="subKanan">Turunan dari Indikator Inisiatif yang dipilih
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-primary btn-sm" id="btnTambahItem"
                                        data-bs-toggle="modal" data-bs-target="#tambahProgram" disabled>
                                        <i class="mdi mdi-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" id="panelKananBody">
                                <div class="text-center text-muted py-4">Pilih Indikator Inisiatif</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL PROGRAM (tambah) --}}
                <div class="modal fade" id="tambahProgram" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">
                                    Tambah Program
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('program.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_indikator_inisiatif" id="inputIdIndikatorInisiatif"
                                    value="{{ old('id_indikator_inisiatif') }}">
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label for="nama_program" class="col-sm-3 col-form-label">Nama Program
                                            <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="nama_program" name="nama_program" rows="4" required>{{ old('nama_program') }}</textarea>
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

                {{-- MODAL PROGRAM (edit) --}}
                <div class="modal fade" id="editProgram" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
                    role="dialog" aria-labelledby="judulEditProgram" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="judulEditProgram">Edit Program</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="formEditProgram" action="" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label for="edit_nama_program" class="col-sm-3 col-form-label">Nama Program <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="edit_nama_program" name="nama_program" rows="4" required></textarea>
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

                {{-- MODAL INDIKATOR PROGRAM (tambah) --}}
                <div class="modal fade" id="tambahIndikatorProgram" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">
                                    Tambah Indikator Program
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('indikator-program.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_program" id="inputIdProgram"
                                    value="{{ old('id_program') }}">
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label for="nama_indikator_program" class="col-sm-3 col-form-label">Nama
                                            Indikator Program
                                            <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="nama_indikator_program" name="nama_indikator_program" rows="4" required>{{ old('nama_indikator_program') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="target_manager" class="col-sm-3 col-form-label">Target
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="target_manager"
                                                name="target_manager" value="{{ old('target_manager') }}"></input>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="aspek" class="col-sm-3 col-form-label">Aspek</label>
                                        <div class="col-sm-9">
                                            <select name="aspek" id="aspek" class="form-select" required>
                                                <option value="" disabled selected>
                                                    Pilih Aspek
                                                </option>
                                                @foreach ($aspekOptions as $aspek)
                                                    <option value="{{ $aspek }}"
                                                        {{ old('aspek') == $aspek ? 'selected' : '' }}>
                                                        {{ $aspek }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="periode_pengukuran" class="col-sm-3 col-form-label">Periode
                                            Pengukuran</label>
                                        <div class="col-sm-9">
                                            <select name="periode_pengukuran" id="periode_pengukuran" class="form-select"
                                                required>
                                                <option value="" disabled selected>
                                                    Pilih Periode Pengukuran
                                                </option>
                                                @foreach ($periodeOptions as $periode)
                                                    <option value="{{ $periode }}"
                                                        {{ old('periode_pengukuran') == $periode ? 'selected' : '' }}>
                                                        {{ $periode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="id_unit_penanggung_jawab" class="col-sm-3 col-form-label">Unit</label>
                                        <div class="col-sm-9">
                                            <select name="id_unit[]" id="id_unit_penanggung_jawab" class="form-control"
                                                multiple>
                                                @foreach ($daftarUnit as $unit)
                                                    <option value="{{ $unit->id_unit }}"
                                                        {{ in_array($unit->id_unit, old('id_unit', [])) ? 'selected' : '' }}>
                                                        {{ $unit->nama_unit }}
                                                    </option>
                                                @endforeach
                                            </select>
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

                {{-- MODAL INDIKATOR PROGRAM (edit) --}}
                <div class="modal fade" id="editIndikatorProgram" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="judulEditIndikatorProgram" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="judulEditIndikatorProgram">Edit Indikator Program</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="formEditIndikatorProgram" action="" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label for="edit_nama_indikator_program" class="col-sm-3 col-form-label">Nama
                                            Indikator
                                            Program <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="edit_nama_indikator_program" name="nama_indikator_program" rows="4"
                                                required></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="edit_target_manager" class="col-sm-3 col-form-label">Target</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="edit_target_manager"
                                                name="target_manager">
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="edit_aspek" class="col-sm-3 col-form-label">Aspek <span
                                                class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <select name="aspek" id="edit_aspek" class="form-select" required>
                                                <option value="" disabled selected>
                                                    Pilih Aspek
                                                </option>
                                                @foreach ($aspekOptions as $aspek)
                                                    <option value="{{ $aspek }}"
                                                        {{ old('aspek') == $aspek ? 'selected' : '' }}>
                                                        {{ $aspek }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="edit_periode_pengukuran" class="col-sm-3 col-form-label">Periode
                                            Pengukuran <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <select name="periode_pengukuran" id="edit_periode_pengukuran"
                                                class="form-select" required>
                                                <option value="" disabled selected>
                                                    Pilih Periode Pengukuran
                                                </option>
                                                @foreach ($periodeOptions as $periode)
                                                    <option value="{{ $periode }}"
                                                        {{ old('periode_pengukuran') == $periode ? 'selected' : '' }}>
                                                        {{ $periode }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="row mb-3">
                                        <label for="edit_id_unit_penanggung_jawab"
                                            class="col-sm-3 col-form-label">Unit</label>
                                        <div class="col-sm-9">
                                            <select name="id_unit[]" id="edit_id_unit_penanggung_jawab"
                                                class="form-control" multiple>
                                                @foreach ($daftarUnit as $unit)
                                                    <option value="{{ $unit->id_unit }}">{{ $unit->nama_unit }}</option>
                                                @endforeach
                                            </select>
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

                {{-- Form tersembunyi untuk hapus (dipanggil setelah konfirmasi SweetAlert) --}}
                <form method="POST" id="formHapus" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script>
        (function() {
            const canManage = @json($canManage);

            const routes = {
                items: "{{ route('program.by-inisiatif', '__ID__') }}",
                itemUpdate: "{{ route('program.update', '__ID__') }}",
                itemDestroy: "{{ route('program.destroy', '__ID__') }}",
                indikatorUpdate: "{{ route('indikator-program.update', '__ID__') }}",
                indikatorDestroy: "{{ route('indikator-program.destroy', '__ID__') }}",
            };

            const F = {
                itemId: 'id_program',
                itemNama: 'nama_program',
                rel: 'indikator_program',
                indId: 'id_indikator_program',
                indNama: 'nama_indikator_program',
                indTarget: 'target_manager',
                child: 'kegiatan_count',
            };
            const labelItem = 'Program';
            const labelIndikator = 'Indikator Program';

            const body = document.getElementById('panelKananBody');
            const subKanan = document.getElementById('subKanan');
            const btnTambah = document.getElementById('btnTambahItem');
            const modalTambahProgram = document.getElementById('tambahProgram');
            const inputIdIndikatorInisiatif = document.getElementById('inputIdIndikatorInisiatif');
            const modalTambahIndikatorProgram = document.getElementById('tambahIndikatorProgram');
            const inputIdProgram = document.getElementById('inputIdProgram');
            const storeKey = 'kpi-tracker:program';

            let selectedId = null;
            let selectedNama = '';
            let itemsCache = [];

            const esc = (s) => String(s ?? '').replace(/[&<>"']/g, (c) => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                '"': '&quot;',
                "'": '&#39;'
            } [c]));
            const cap = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : '';
            const url = (tpl, id) => tpl.replace('__ID__', id);
            const ambilKataPertama = (teks) => String(teks ?? '').trim().split(/\s+/)[0] || '-';

            // ---------- Panel kiri ----------
            document.querySelectorAll('.js-parent').forEach((el) => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    selectParent(el);
                });
            });

            function selectParent(el) {
                document.querySelectorAll('.js-parent').forEach((x) => x.classList.remove('bg-primary-subtle'));
                el.classList.add('bg-primary-subtle');
                selectedId = el.dataset.id;
                selectedNama = el.dataset.nama;
                sessionStorage.setItem(storeKey, selectedId);
                subKanan.textContent = 'Turunan dari: ' + selectedNama;
                if (btnTambah) btnTambah.disabled = false;
                if (inputIdIndikatorInisiatif) inputIdIndikatorInisiatif.value = selectedId;
                loadItems();
            }

            // ---------- Panel kanan ----------
            function loadItems() {
                body.innerHTML = '<div class="text-center text-muted py-4">Memuat...</div>';
                fetch(url(routes.items, selectedId), {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    })
                    .then((r) => r.json())
                    .then((data) => {
                        itemsCache = data;
                        render(itemsCache);
                    })
                    .catch(() => {
                        body.innerHTML = '<div class="text-center text-danger py-4">Gagal memuat data.</div>';
                    });
            }

            function render(items) {
                if (!items.length) {
                    body.innerHTML = '<div class="text-center text-muted py-4">Belum terdapat ' +
                        esc(labelItem) + ' pada indikator ini.</div>';
                    return;
                }

                let html = '<div class="accordion" id="accItem">';
                items.forEach((it, i) => {
                    // const open = i === 0;
                    const inds = it[F.rel] || [];
                    const id = it[F.itemId];

                    html += `
                    <div class="accordion-item mb-2">
                <h2 class="accordion-header d-flex align-items-center">
                    <button class="accordion-button collapsed" type="button"
                            data-bs-toggle="collapse" data-bs-target="#item${id}">
                        <span class="badge bg-primary me-2">${String(i + 1).padStart(2, '0')}</span>
                        <span class="flex-grow-1 fw-medium">${esc(it[F.itemNama])}</span>
                        <span class="text-muted small me-3">${inds.length} indikator</span>
                    </button>
                    ${canManage ? `
                                                                        <div class="dropdown border-start px-2">
                                                                            <button class="btn btn-sm" type="button"
                                                                                    data-bs-toggle="dropdown"
                                                                                    aria-expanded="false" title="Aksi ${esc(labelItem)}">
                                                                                <i class="bx bx-dots-vertical-rounded font-size-20"></i>
                                                                            </button>
                                                                            <ul class="dropdown-menu dropdown-menu-end">
                                                                                <li>
                                                                                    <button type="button" class="dropdown-item js-edit-item" data-id="${id}">
                                                                                        <i class="mdi mdi-pencil me-2"></i>Edit ${esc(labelItem)}
                                                                                    </button>
                                                                                </li>
                                                                                <li>
                                                                                    <button type="button" class="dropdown-item text-danger js-hapus-item" data-id="${id}">
                                                                                        <i class="mdi mdi-delete me-2"></i>Hapus ${esc(labelItem)}
                                                                                    </button>
                                                                                </li>
                                                                            </ul>
                                                                        </div>` : ''}
                </h2>
                <div id="item${id}" class="accordion-collapse collapse">
                    <div class="accordion-body">
                        ${inds.map((n, indeksIndikator) => `
                                                                        <div class="d-flex justify-content-between align-items-start border-bottom py-2">
                                                                            <div class="d-flex gap-2">
                                                                                <span class="badge bg-info-subtle text-info align-self-start">${String(indeksIndikator + 1).padStart(2, '0')}</span>                                            <div>
                                                                                    <div>${esc(n[F.indNama])}</div>
                                                                                    <div class="text-muted small">Target : ${esc(n[F.indTarget] || '-')}</div>
                                                                                    <div class="text-muted small">Aspek : ${esc(cap(n.aspek) || '-')}</div>
                                                                                    <div class="text-muted small">Periode Pengukuran : ${esc(cap(n.periode_pengukuran) || '-')}</div>
                                                                                    <div class="text-muted small">Unit : ${
                                                                                        (n.pic_unit || []).length
                                                                                            ? n.pic_unit.map((picUnit) => {
                                                                                                const namaUnitLengkap = picUnit.unit ? picUnit.unit.nama_unit : '';
                                                                                                return `<span class="badge bg-info-subtle rounded-pill text-info" title="${esc(namaUnitLengkap)}">${esc(ambilKataPertama(namaUnitLengkap))}</span>`;
                                                                                            }).join(' ')
                                                                                            : '-'
                                                                                    }</div>
                                                                                </div>
                                                                            </div>
                                                                            ${canManage ? `
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-light js-edit-indikator" data-item="${id}" data-id="${n[F.indId]}"><i class="mdi mdi-pencil"></i></button>
                                <button class="btn btn-sm btn-light js-hapus-indikator" data-id="${n[F.indId]}"><i class="mdi mdi-delete"></i></button>
                            </div>` : ''}
                                                                            </div>`).join('')}

                        ${canManage ? `
                                                                            <button class="btn btn-link btn-sm px-0 mt-2" data-bs-toggle="modal" data-bs-target="#tambahIndikatorProgram" data-item="${id}">
                                                                                <i class="mdi mdi-plus"></i> Tambah ${esc(labelIndikator)}
                                                                            </button>` : ''}
                    </div>
                </div>
            </div>`;
                });
                html += '</div>';
                body.innerHTML = html;
            }

            function restoreSelection() {
                const saved = sessionStorage.getItem(storeKey);
                if (!saved) return;
                const el = document.querySelector('.js-parent[data-id="' + saved + '"]');
                if (el) selectParent(el);
            }

            if (!canManage) {
                restoreSelection();
                return;
            }

            const opsiChoices = {
                removeItemButton: true,
                placeholder: true,
                placeholderValue: 'Tambah PIC',
                searchEnabled: true
            };

            const choicesUnitTambah = new Choices('#id_unit_penanggung_jawab', opsiChoices);
            const choicesUnitEdit = new Choices('#edit_id_unit_penanggung_jawab', opsiChoices);

            // ---------- Modal Tambah Program ----------
            if (modalTambahProgram) {
                modalTambahProgram.addEventListener('show.bs.modal', (e) => {
                    if (!selectedId) {
                        e.preventDefault();
                        return;
                    }
                    inputIdIndikatorInisiatif.value = selectedId;
                });

                modalTambahProgram.addEventListener('hidden.bs.modal', () => {
                    document.getElementById('nama_program').value = '';
                });
            }

            // ---------- Modal tambah indikator program ----------
            if (modalTambahIndikatorProgram) {
                modalTambahIndikatorProgram.addEventListener('show.bs.modal', (e) => {
                    const trigger = e.relatedTarget;
                    if (!trigger || !trigger.dataset.item) {
                        e.preventDefault();
                        return;
                    }
                    inputIdProgram.value = trigger.dataset.item;
                });

                modalTambahIndikatorProgram.addEventListener('hidden.bs.modal', () => {
                    document.getElementById('nama_indikator_program').value = '';
                    document.getElementById('target_manager').value = '';
                    document.getElementById('aspek').value = '';
                    document.getElementById('periode_pengukuran').value = '';
                    choicesUnitTambah.removeActiveItems();
                });
            }

            // ---------- Modal edit program ----------
            const modalEditProgram = document.getElementById('editProgram');
            const formEditProgram = document.getElementById('formEditProgram');
            const inputEditNamaProgram = document.getElementById('edit_nama_program');

            function bukaModalEditProgram(program) {
                formEditProgram.action = url(routes.itemUpdate, program[F.itemId]);
                inputEditNamaProgram.value = program[F.itemNama];
                bootstrap.Modal.getOrCreateInstance(modalEditProgram).show();
            }

            // ---------- Modal edit indikator program ----------
            const modalEditIndikatorProgram = document.getElementById('editIndikatorProgram');
            const formEditIndikatorProgram = document.getElementById('formEditIndikatorProgram');
            const inputEditNamaIndikatorProgram = document.getElementById('edit_nama_indikator_program');
            const inputEditTargetIndikatorProgram = document.getElementById('edit_target_manager');
            const selectEditAspek = document.getElementById('edit_aspek');
            const selectEditPeriodePengukuran = document.getElementById('edit_periode_pengukuran');
            const selectEditUnit = document.getElementById('edit_id_unit_penanggung_jawab');

            function bukaModalEditIndikatorProgram(indikatorProgram) {
                formEditIndikatorProgram.action = url(routes.indikatorUpdate, indikatorProgram[F.indId]);
                inputEditNamaIndikatorProgram.value = indikatorProgram[F.indNama];
                inputEditTargetIndikatorProgram.value = indikatorProgram[F.indTarget] || '';
                selectEditAspek.value = indikatorProgram.aspek || '';
                selectEditPeriodePengukuran.value = indikatorProgram.periode_pengukuran || '';
                const daftarIdUnitTerpilih = (indikatorProgram.pic_unit || [])
                    .map((picUnit) => String(picUnit.id_unit));
                choicesUnitEdit.removeActiveItems();
                choicesUnitEdit.setChoiceByValue(daftarIdUnitTerpilih);
                bootstrap.Modal.getOrCreateInstance(modalEditIndikatorProgram).show();
            }

            // ---------- Hapus (SweetAlert) ----------
            function konfirmasiHapus(action) {
                Swal.fire({
                    title: 'Hapus data?',
                    text: 'Data yang sudah dihapus tidak dapat dikembalikan. Data yang masih memiliki data turunan tidak dapat dihapus.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#2ab57d',
                    cancelButtonColor: '#fd625e',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal',
                }).then((r) => {
                    if (r.isConfirmed) {
                        const f = document.getElementById('formHapus');
                        f.action = action;
                        f.submit();
                    }
                });
            }

            // ---------- Handler klik panel kanan ----------
            body.addEventListener('click', (e) => {
                const tombol = e.target.closest('button');
                if (!tombol) return;

                if (tombol.classList.contains('js-edit-item')) {
                    const program = itemsCache.find((x) => String(x[F.itemId]) === tombol.dataset.id);
                    if (program) bukaModalEditProgram(program);
                }

                if (tombol.classList.contains('js-hapus-item')) {
                    konfirmasiHapus(url(routes.itemDestroy, tombol.dataset.id));
                }

                if (tombol.classList.contains('js-edit-indikator')) {
                    const program = itemsCache.find((x) => String(x[F.itemId]) === tombol.dataset.item);
                    const indikatorProgram = program ?
                        (program[F.rel] || []).find((x) => String(x[F.indId]) === tombol.dataset.id) :
                        null;
                    if (indikatorProgram) bukaModalEditIndikatorProgram(indikatorProgram);
                }

                if (tombol.classList.contains('js-hapus-indikator')) {
                    konfirmasiHapus(url(routes.indikatorDestroy, tombol.dataset.id));
                }
            });

            restoreSelection();
        })();
    </script>
@endsection
