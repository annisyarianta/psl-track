@extends('layouts.app')

@section('title')
    Kegiatan Asisten Manager | PSL Track
@endsection

@section('content')

    @php
        $canManage = in_array(auth()->user()->role, ['asmen', 'manager']);
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
                                    <li class="breadcrumb-item">
                                        Manager
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Ass. Manager
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    {{-- PANEL KIRI: Indikator Program dari Manager --}}
                    <div class="col-lg-5">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Indikator Program</h5>
                                    <p class="text-muted mb-0">Dari Manager</p>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                @forelse ($programs as $program)
                                    @if ($program->indikatorProgram->count())
                                        <div class="px-3 pt-3 pb-1 text-primary small">{{ $program->nama_program }}</div>
                                        <div class="list-group list-group-flush">
                                            @foreach ($program->indikatorProgram as $ind)
                                                <a href="#" class="list-group-item list-group-item-action js-parent"
                                                    data-id="{{ $ind->id_indikator_program }}"
                                                    data-nama="{{ $ind->nama_indikator_program }}">
                                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                                        <div>
                                                            <h6 class="mb-1">{{ $ind->nama_indikator_program }}</h6>
                                                            <p class="text-muted small mb-0">Target :
                                                                {{ $ind->target_manager ?? '-' }}
                                                            </p>
                                                        </div>
                                                        <span class="badge bg-info">
                                                            {{ $ind->kegiatan_count ?? 0 }}
                                                            Kegiatan
                                                        </span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center text-muted p-4">Belum terdapat Indikator Program dari Manager.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- PANEL KANAN: Kegiatan milik Ass. Manager --}}
                    <div class="col-lg-7">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Kegiatan</h5>
                                    <p class="text-muted mb-0" id="subKanan">Turunan dari Indikator Program yang dipilih
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-primary btn-sm" id="btnTambahItem"
                                        data-bs-toggle="modal" data-bs-target="#tambahKegiatan" disabled>
                                        <i class="mdi mdi-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" id="panelKananBody">
                                <div class="text-center text-muted py-4">Pilih Indikator Program</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($canManage)
                    {{-- MODAL KEGIATAN (tambah) --}}
                    <div class="modal fade" id="tambahKegiatan" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">
                                        Tambah Kegiatan
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('kegiatan.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_indikator_program" id="inputIdIndikatorProgram"
                                        value="{{ old('id_indikator_program') }}">
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <label for="nama_kegiatan" class="col-sm-3 col-form-label">Nama Kegiatan
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="nama_kegiatan" name="nama_kegiatan" rows="4" required>{{ old('nama_kegiatan') }}</textarea>
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

                    {{-- MODAL KEGIATAN (edit) --}}
                    <div class="modal fade" id="editKegiatan" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" role="dialog" aria-labelledby="judulEditKegiatan" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="judulEditKegiatan">Edit Kegiatan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form id="formEditKegiatan" action="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <label for="edit_nama_kegiatan" class="col-sm-3 col-form-label">Nama Kegiatan
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="edit_nama_kegiatan" name="nama_kegiatan" rows="4" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    {{-- MODAL INDIKATOR KEGIATAN (tambah) --}}
                    <div class="modal fade" id="tambahIndikatorKegiatan" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">
                                        Tambah Indikator Kegiatan
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('indikator-kegiatan.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_kegiatan" id="inputIdKegiatan"
                                        value="{{ old('id_kegiatan') }}">
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <label for="nama_indikator_kegiatan" class="col-sm-3 col-form-label">Nama
                                                Indikator Kegiatan
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="nama_indikator_kegiatan" name="nama_indikator_kegiatan" rows="4" required>{{ old('nama_indikator_kegiatan') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label for="target_asmen" class="col-sm-3 col-form-label">Target
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="target_asmen"
                                                    name="target_asmen" value="{{ old('target_asmen') }}"></input>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="id_staff_penanggung_jawab"
                                                class="col-sm-3 col-form-label">PIC</label>
                                            <div class="col-sm-9">
                                                <select name="id_user[]" id="id_staff_penanggung_jawab"
                                                    class="form-select" multiple>
                                                    @foreach ($daftarStaff as $staff)
                                                        <option value="{{ $staff->id_user }}"
                                                            {{ in_array($staff->id_user, old('id_user', [])) ? 'selected' : '' }}>
                                                            {{ $staff->nama }}
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

                    {{-- MODAL INDIKATOR KEGIATAN (edit) --}}
                    <div class="modal fade" id="editIndikatorKegiatan" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" role="dialog"
                        aria-labelledby="judulEditIndikatorKegiatan" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="judulEditIndikatorKegiatan">Edit Indikator Kegiatan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form id="formEditIndikatorKegiatan" action="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <label for="edit_nama_indikator_kegiatan" class="col-sm-3 col-form-label">Nama
                                                Indikator
                                                Kegiatan <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="edit_nama_indikator_kegiatan" name="nama_indikator_kegiatan" rows="4"
                                                    required></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-3">
                                            <label
                                                for="edit_target_asmen"
                                                class="col-sm-3
                                                col-form-label">Target</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="edit_target_asmen"
                                                    name="target_asmen">
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="edit_id_staff_penanggung_jawab"
                                                class="col-sm-3 col-form-label">Staff</label>
                                            <div class="col-sm-9">
                                                <select name="id_staff[]" id="edit_id_staff_penanggung_jawab"
                                                    class="form-select" multiple>
                                                    @foreach ($daftarStaff as $staff)
                                                        <option value="{{ $staff->id_user }}">{{ $staff->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light"
                                            data-bs-dismiss="modal">Batal</button>
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
                @endif

            </div>
        </div>
    </div>

    <script src="{{ asset('assets/libs/choices.js/public/assets/scripts/choices.min.js') }}"></script>
    <script>
        (function() {
            const canManage = @json($canManage);

            const routes = {
                items: "{{ route('kegiatan.by-program', '__ID__') }}",
                itemUpdate: "{{ route('kegiatan.update', '__ID__') }}",
                itemDestroy: "{{ route('kegiatan.destroy', '__ID__') }}",
                indikatorUpdate: "{{ route('indikator-kegiatan.update', '__ID__') }}",
                indikatorDestroy: "{{ route('indikator-kegiatan.destroy', '__ID__') }}",
            };

            const F = {
                itemId: 'id_kegiatan',
                itemNama: 'nama_kegiatan',
                rel: 'indikator_kegiatan',
                indId: 'id_indikator_kegiatan',
                indNama: 'nama_indikator_kegiatan',
                indTarget: 'target_asmen',
                child: 'sub_kegiatan_count',
            };
            const labelItem = 'Kegiatan';
            const labelIndikator = 'Indikator Kegiatan';

            const body = document.getElementById('panelKananBody');
            const subKanan = document.getElementById('subKanan');
            const btnTambah = document.getElementById('btnTambahItem');
            const modalTambahKegiatan = document.getElementById('tambahKegiatan');
            const inputIdIndikatorProgram = document.getElementById('inputIdIndikatorProgram');
            const modalTambahIndikatorKegiatan = document.getElementById('tambahIndikatorKegiatan');
            const inputIdKegiatan = document.getElementById('inputIdKegiatan');
            const storeKey = 'kpi-tracker:kegiatan';

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
            const ambilKataAwal = (teks, jumlahKata = 1) =>
                String(teks ?? '').trim().split(/\s+/).slice(0, jumlahKata).join(' ') || '-';

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
                if (inputIdIndikatorProgram) inputIdIndikatorProgram.value = selectedId;
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
                                                        <button type="button" class="dropdown-item js-edit-kegiatan" data-id="${id}">
                                                            <i class="mdi mdi-pencil me-2"></i>Edit ${esc(labelItem)}
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <button type="button" class="dropdown-item text-danger js-hapus-kegiatan" data-id="${id}">
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
                                                        <div class="text-muted small">PIC : ${
                                                            (n.pic_staff || []).length
                                                                ? n.pic_staff.map((picStaff) => {
                                                                    const namaStaffLengkap = picStaff.user ? picStaff.user.nama : '';
                                                                    return `<span class="badge bg-info-subtle rounded-pill text-info" title="${esc(namaStaffLengkap)}">${esc(ambilKataAwal(namaStaffLengkap, 2))}</span>`;
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
                                                                                <button class="btn btn-link btn-sm px-0 mt-2 js-tambah-indikator" data-bs-toggle="modal" data-bs-target="#tambahIndikatorKegiatan" data-item="${id}">
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

            const choicesStaffTambah = new Choices('#id_staff_penanggung_jawab', opsiChoices);
            const choicesStaffEdit = new Choices('#edit_id_staff_penanggung_jawab', opsiChoices);

            // ---------- Modal Tambah Kegiatan ----------
            if (modalTambahKegiatan) {
                modalTambahKegiatan.addEventListener('show.bs.modal', (e) => {
                    if (!selectedId) {
                        e.preventDefault();
                        return;
                    }
                    inputIdIndikatorProgram.value = selectedId;
                });

                modalTambahKegiatan.addEventListener('hidden.bs.modal', () => {
                    document.getElementById('nama_kegiatan').value = '';
                });
            }

            // ---------- Modal tambah indikator kegiatan ----------
            if (modalTambahIndikatorKegiatan) {
                modalTambahIndikatorKegiatan.addEventListener('show.bs.modal', (e) => {
                    const trigger = e.relatedTarget;
                    if (!trigger || !trigger.dataset.item) {
                        e.preventDefault();
                        return;
                    }
                    inputIdKegiatan.value = trigger.dataset.item;
                });

                modalTambahIndikatorKegiatan.addEventListener('hidden.bs.modal', () => {
                    document.getElementById('nama_indikator_kegiatan').value = '';
                    document.getElementById('target_asmen').value = '';
                    choicesStaffTambah.removeActiveItems();
                });
            }

            // ---------- Modal edit kegiatan ----------
            const modalEditKegiatan = document.getElementById('editKegiatan');
            const formEditKegiatan = document.getElementById('formEditKegiatan');
            const inputEditNamaKegiatan = document.getElementById('edit_nama_kegiatan');

            function bukaModalEditKegiatan(kegiatan) {
                formEditKegiatan.action = url(routes.itemUpdate, kegiatan[F.itemId]);
                inputEditNamaKegiatan.value = kegiatan[F.itemNama];
                bootstrap.Modal.getOrCreateInstance(modalEditKegiatan).show();
            }

            // ---------- Modal edit indikator kegiatan ----------
            const modalEditIndikatorKegiatan = document.getElementById('editIndikatorKegiatan');
            const formEditIndikatorKegiatan = document.getElementById('formEditIndikatorKegiatan');
            const inputEditNamaIndikatorKegiatan = document.getElementById('edit_nama_indikator_kegiatan');
            const inputEditTargetIndikatorKegiatan = document.getElementById('edit_target_asmen');
            const selectEditStaff = document.getElementById('edit_id_staff_penanggung_jawab');

            function bukaModalEditIndikatorKegiatan(indikatorKegiatan) {
                formEditIndikatorKegiatan.action = url(routes.indikatorUpdate, indikatorKegiatan[F.indId]);
                inputEditNamaIndikatorKegiatan.value = indikatorKegiatan[F.indNama];
                inputEditTargetIndikatorKegiatan.value = indikatorKegiatan[F.indTarget] || '';
                const daftarIdStaffTerpilih = (indikatorKegiatan.pic_staff || [])
                    .map((picStaff) => String(picStaff.id_user));
                choicesStaffEdit.removeActiveItems();
                choicesStaffEdit.setChoiceByValue(daftarIdStaffTerpilih);
                bootstrap.Modal.getOrCreateInstance(modalEditIndikatorKegiatan).show();
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
                    confirmButtonText: 'Ya, hapus',
                    cancelButtonText: 'Batal',
                }).then((r) => {
                    if (r.isConfirmed) {
                        const f = document.getElementById('formHapus');
                        f.action = action;
                        f.submit();
                    }
                });
            }

            // ---------- Klik tombol di panel kanan ----------
            body.addEventListener('click', (e) => {
                const t = e.target.closest('button');
                if (!t) return;

                if (t.classList.contains('js-edit-kegiatan')) {
                    const kegiatan = itemsCache.find((x) => String(x[F.itemId]) === t.dataset.id);
                    if (kegiatan) bukaModalEditKegiatan(kegiatan);
                }
                if (t.classList.contains('js-hapus-kegiatan')) {
                    konfirmasiHapus(url(routes.itemDestroy, t.dataset.id));
                }
                if (t.classList.contains('js-edit-indikator')) {
                    const kegiatan = itemsCache.find((x) => String(x[F.itemId]) === t.dataset.item);
                    const indikatorKegiatan = kegiatan ?
                        (kegiatan[F.rel] || []).find((x) => String(x[F.indId]) === t.dataset.id) :
                        null;
                    if (indikatorKegiatan) bukaModalEditIndikatorKegiatan(indikatorKegiatan);
                }
                if (t.classList.contains('js-hapus-indikator')) {
                    konfirmasiHapus(url(routes.indikatorDestroy, t.dataset.id));
                }
            });

            restoreSelection();
        })();
    </script>
@endsection
