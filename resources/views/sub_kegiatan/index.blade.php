@extends('layouts.app')

@section('title')
    Sub Kegiatan Staff | PSL Track
@endsection

@section('content')

    @php
        $canManage = in_array(auth()->user()->role, ['staff', 'manager']);
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
                                        Dir. Utama
                                    </li>
                                    <li class="breadcrumb-item">
                                        Dir. Bagian
                                    </li>
                                    <li class="breadcrumb-item">
                                        Manager
                                    </li>
                                    <li class="breadcrumb-item">
                                        Ass. Manager
                                    </li>
                                    <li class="breadcrumb-item active">
                                        Staff
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    {{-- PANEL KIRI: Indikator Kegiatan dari Ass. Manager --}}
                    <div class="col-lg-5">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Indikator Kegiatan</h5>
                                    <p class="text-muted mb-0">Dari Asisten Manager</p>
                                </div>
                            </div>
                            <div class="card-body p-0">
                                @forelse ($kegiatans as $kegiatan)
                                    @if ($kegiatan->indikatorKegiatan->count())
                                        <div class="px-3 pt-3 pb-1 text-primary small">{{ $kegiatan->nama_kegiatan }}</div>
                                        <div class="list-group list-group-flush">
                                            @foreach ($kegiatan->indikatorKegiatan as $ind)
                                                <a href="#" class="list-group-item list-group-item-action js-parent"
                                                    data-id="{{ $ind->id_indikator_kegiatan }}"
                                                    data-nama="{{ $ind->nama_indikator_kegiatan }}">
                                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                                        <div>
                                                            <h6 class="mb-1">{{ $ind->nama_indikator_kegiatan }}</h6>
                                                            <p class="text-muted small mb-0">Target :
                                                                {{ $ind->target_asmen ?? '-' }}
                                                            </p>
                                                        </div>
                                                        <span class="badge bg-info">{{ $ind->sub_kegiatan_count }}
                                                            Sub
                                                            Kegiatan</span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center text-muted p-4">Belum terdapat Indikator Kegiatan dari Ass.
                                        Manager.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- PANEL KANAN: Sub Kegiatan milik Staff --}}
                    <div class="col-lg-7">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Sub Kegiatan</h5>
                                    <p class="text-muted mb-0" id="subKanan">Turunan dari Indikator Kegiatan yang dipilih
                                    </p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-primary btn-sm" id="btnTambahItem"
                                        data-bs-toggle="modal" data-bs-target="#tambahSubKegiatan" disabled>
                                        <i class="mdi mdi-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" id="panelKananBody">
                                <div class="text-center text-muted py-4">Pilih Indikator Kegiatan</div>
                            </div>
                        </div>
                    </div>
                </div>

                @if ($canManage)
                    {{-- MODAL SUB KEGIATAN (tambah) --}}
                    <div class="modal fade" id="tambahSubKegiatan" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">
                                        Tambah Sub Kegiatan
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('sub-kegiatan.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_indikator_kegiatan" id="inputIdIndikatorKegiatan"
                                        value="{{ old('id_indikator_kegiatan') }}">
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <label for="judul_sub_kegiatan" class="col-sm-3 col-form-label">Judul Sub
                                                Kegiatan
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="judul_sub_kegiatan" name="judul_sub_kegiatan" rows="2" required>{{ old('judul_sub_kegiatan') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="isi_sub_kegiatan" class="col-sm-3 col-form-label">Isi Sub Kegiatan
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="isi_sub_kegiatan" name="isi_sub_kegiatan" rows="4">{{ old('isi_sub_kegiatan') }}</textarea>
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

                    {{-- MODAL SUB KEGIATAN (edit) --}}
                    <div class="modal fade" id="editSubKegiatan" data-bs-backdrop="static" data-bs-keyboard="false"
                        tabindex="-1" role="dialog" aria-labelledby="judulEditSubKegiatan" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="judulEditSubKegiatan">Edit Sub Kegiatan</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form id="formEditSubKegiatan" action="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <label for="edit_judul_sub_kegiatan" class="col-sm-3 col-form-label">Judul Sub
                                                Kegiatan
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="edit_judul_sub_kegiatan" name="judul_sub_kegiatan" rows="2" required></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="edit_isi_sub_kegiatan" class="col-sm-3 col-form-label">Isi Sub
                                                Kegiatan
                                            </label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="edit_isi_sub_kegiatan" name="isi_sub_kegiatan" rows="4"></textarea>
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

                    {{-- MODAL INDIKATOR SUB KEGIATAN (tambah) --}}
                    <div class="modal fade" id="tambahIndikatorSubKegiatan" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">
                                        Tambah Indikator Sub Kegiatan
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form action="{{ route('indikator-sub-kegiatan.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="id_sub_kegiatan" id="inputIdSubKegiatan"
                                        value="{{ old('id_sub_kegiatan') }}">
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <label for="nama_indikator_sub_kegiatan" class="col-sm-3 col-form-label">
                                                Indikator Sub Kegiatan
                                                <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="nama_indikator_sub_kegiatan" name="nama_indikator_sub_kegiatan" rows="4"
                                                    required>{{ old('nama_indikator_sub_kegiatan') }}</textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="target_staff" class="col-sm-3 col-form-label">Target
                                            </label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="target_staff"
                                                    name="target_staff" value="{{ old('target_staff') }}"></input>
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

                    {{-- MODAL INDIKATOR SUB KEGIATAN (edit) --}}
                    <div class="modal fade" id="editIndikatorSubKegiatan" data-bs-backdrop="static"
                        data-bs-keyboard="false" tabindex="-1" role="dialog"
                        aria-labelledby="judulEditIndikatorSubKegiatan" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="judulEditIndikatorSubKegiatan">Edit Indikator Sub Kegiatan
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <form id="formEditIndikatorSubKegiatan" action="" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-body">
                                        <div class="row mb-3">
                                            <label for="edit_nama_indikator_sub_kegiatan"
                                                class="col-sm-3 col-form-label">
                                                Indikator Sub
                                                Kegiatan <span class="text-danger">*</span></label>
                                            <div class="col-sm-9">
                                                <textarea class="form-control" id="edit_nama_indikator_sub_kegiatan" name="nama_indikator_sub_kegiatan"
                                                    rows="4" required></textarea>
                                            </div>
                                        </div>
                                        <div class="row mb-2">
                                            <label for="edit_target_staff"
                                                class="col-sm-3
                                                col-form-label">Target</label>
                                            <div class="col-sm-9">
                                                <input type="text" class="form-control" id="edit_target_staff"
                                                    name="target_staff">
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

    <script>
        (function() {
            const canManage = @json($canManage);

            const routes = {
                items: "{{ route('sub-kegiatan.by-indikator-kegiatan', '__ID__') }}",
                itemUpdate: "{{ route('sub-kegiatan.update', '__ID__') }}",
                itemDestroy: "{{ route('sub-kegiatan.destroy', '__ID__') }}",
                indikatorUpdate: "{{ route('indikator-sub-kegiatan.update', '__ID__') }}",
                indikatorDestroy: "{{ route('indikator-sub-kegiatan.destroy', '__ID__') }}",
            };

            const F = {
                itemId: 'id_sub_kegiatan',
                itemNama: 'judul_sub_kegiatan',
                itemIsi: 'isi_sub_kegiatan',
                rel: 'indikator_sub_kegiatan',
                indId: 'id_indikator_sub_kegiatan',
                indNama: 'nama_indikator_sub_kegiatan',
                indTarget: 'target_staff',
            };
            const labelItem = 'Sub Kegiatan';
            const labelIndikator = 'Indikator Sub Kegiatan';

            const body = document.getElementById('panelKananBody');
            const subKanan = document.getElementById('subKanan');
            const btnTambah = document.getElementById('btnTambahItem');
            const modalTambahSubKegiatan = document.getElementById('tambahSubKegiatan');
            const inputIdIndikatorKegiatan = document.getElementById('inputIdIndikatorKegiatan');
            const modalTambahIndikatorSubKegiatan = document.getElementById('tambahIndikatorSubKegiatan');
            const inputIdSubKegiatan = document.getElementById('inputIdSubKegiatan');
            const storeKey = 'kpi-tracker:sub-kegiatan';

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
            const url = (tpl, id) => tpl.replace('__ID__', id);
            const cap = (s) => s ? s.charAt(0).toUpperCase() + s.slice(1) : '';

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
                if (inputIdIndikatorKegiatan) inputIdIndikatorKegiatan.value = selectedId;
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
                                <span class="badge bg-primary me-2 flex-shrink-0">${String(i + 1).padStart(2, '0')}</span>
                                <span class="flex-grow-1 fw-medium">${esc(it[F.itemNama])}</span>
                                <span class="text-muted small text-nowrap flex-shrink-0 ms-3 me-3">${inds.length} indikator</span>
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
                                                <button type="button" class="dropdown-item js-edit-sub-kegiatan" data-id="${id}">
                                                    <i class="mdi mdi-pencil me-2"></i>Edit ${esc(labelItem)}
                                                </button>
                                            </li>
                                            <li>
                                                <button type="button" class="dropdown-item text-danger js-hapus-sub-kegiatan" data-id="${id}">
                                                    <i class="mdi mdi-delete me-2"></i>Hapus ${esc(labelItem)}
                                                </button>
                                            </li>
                                        </ul>
                                    </div>` : ''}
                        </h2>
                        <div id="item${id}" class="accordion-collapse collapse">
                            <div class="accordion-body">
                                <div class="mb-3 text-muted small" style="white-space: pre-line;">${esc(it[F.itemIsi])}</div>

                                ${inds.map((n, indeksIndikator) => `
                                        <div class="d-flex justify-content-between align-items-start border-bottom py-2">
                                            <div class="d-flex gap-2">
                                                <span class="badge bg-info-subtle text-info align-self-start">${String(indeksIndikator + 1).padStart(2, '0')}</span>
                                                <div>
                                                    <div>${esc(n[F.indNama])}</div>
                                                    <div class="text-muted small">Target : ${esc(n[F.indTarget] || '-')}</div>
                                                </div>
                                            </div>
                                            ${canManage ? `
                                            <div class="d-flex gap-1">
                                                <button class="btn btn-sm btn-light js-edit-indikator" data-item="${id}" data-id="${n[F.indId]}"><i class="mdi mdi-pencil"></i></button>
                                                <button class="btn btn-sm btn-light js-hapus-indikator" data-id="${n[F.indId]}"><i class="mdi mdi-delete"></i></button>
                                            </div>` : ''}
                                        </div>`).join('')}

                                ${canManage ? `
                                        <div class="mt-2">
                                            <button class="btn btn-link btn-sm px-0 js-tambah-indikator" data-bs-toggle="modal" data-bs-target="#tambahIndikatorSubKegiatan" data-item="${id}">
                                                <i class="mdi mdi-plus"></i> Tambah ${esc(labelIndikator)}
                                            </button>
                                        </div>` : ''}
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

            // ---------- Modal Tambah Sub Kegiatan ----------
            if (modalTambahSubKegiatan) {
                modalTambahSubKegiatan.addEventListener('show.bs.modal', (e) => {
                    if (!selectedId) {
                        e.preventDefault();
                        return;
                    }
                    inputIdIndikatorKegiatan.value = selectedId;
                });

                modalTambahSubKegiatan.addEventListener('hidden.bs.modal', () => {
                    document.getElementById('judul_sub_kegiatan').value = '';
                    document.getElementById('isi_sub_kegiatan').value = '';
                });
            }

            // ---------- Modal tambah indikator kegiatan ----------
            if (modalTambahIndikatorSubKegiatan) {
                modalTambahIndikatorSubKegiatan.addEventListener('show.bs.modal', (e) => {
                    const trigger = e.relatedTarget;
                    if (!trigger || !trigger.dataset.item) {
                        e.preventDefault();
                        return;
                    }
                    inputIdSubKegiatan.value = trigger.dataset.item;
                });

                modalTambahIndikatorSubKegiatan.addEventListener('hidden.bs.modal', () => {
                    document.getElementById('nama_indikator_sub_kegiatan').value = '';
                    document.getElementById('target_staff').value = '';
                });
            }

            // ---------- Modal edit sub kegiatan ----------
            const modalEditSubKegiatan = document.getElementById('editSubKegiatan');
            const formEditSubKegiatan = document.getElementById('formEditSubKegiatan');
            const inputEditJudulSubKegiatan = document.getElementById('edit_judul_sub_kegiatan');
            const inputEditIsiSubKegiatan = document.getElementById('edit_isi_sub_kegiatan');

            function bukaModalEditSubKegiatan(subkegiatan) {
                formEditSubKegiatan.action = url(routes.itemUpdate, subkegiatan[F.itemId]);
                inputEditJudulSubKegiatan.value = subkegiatan[F.itemNama];
                inputEditIsiSubKegiatan.value = subkegiatan[F.itemIsi];
                bootstrap.Modal.getOrCreateInstance(modalEditSubKegiatan).show();
            }

            // ---------- Modal edit indikator kegiatan ----------
            const modalEditIndikatorSubKegiatan = document.getElementById('editIndikatorSubKegiatan');
            const formEditIndikatorSubKegiatan = document.getElementById('formEditIndikatorSubKegiatan');
            const inputEditNamaIndikatorSubKegiatan = document.getElementById('edit_nama_indikator_sub_kegiatan');
            const inputEditTargetIndikatorSubKegiatan = document.getElementById('edit_target_staff');

            function bukaModalEditIndikatorSubKegiatan(indikatorSubKegiatan) {
                formEditIndikatorSubKegiatan.action = url(routes.indikatorUpdate, indikatorSubKegiatan[F.indId]);
                inputEditNamaIndikatorSubKegiatan.value = indikatorSubKegiatan[F.indNama];
                inputEditTargetIndikatorSubKegiatan.value = indikatorSubKegiatan[F.indTarget] || '';
                bootstrap.Modal.getOrCreateInstance(modalEditIndikatorSubKegiatan).show();
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

                if (t.classList.contains('js-edit-sub-kegiatan')) {
                    const subkegiatan = itemsCache.find((x) => String(x[F.itemId]) === t.dataset.id);
                    if (subkegiatan) bukaModalEditSubKegiatan(subkegiatan);
                }
                if (t.classList.contains('js-hapus-sub-kegiatan')) {
                    konfirmasiHapus(url(routes.itemDestroy, t.dataset.id));
                }
                if (t.classList.contains('js-edit-indikator')) {
                    const subkegiatan = itemsCache.find((x) => String(x[F.itemId]) === t.dataset.item);
                    const indikatorSubKegiatan = subkegiatan ?
                        (subkegiatan[F.rel] || []).find((x) => String(x[F.indId]) === t.dataset.id) :
                        null;
                    if (indikatorSubKegiatan) bukaModalEditIndikatorSubKegiatan(indikatorSubKegiatan);
                }
                if (t.classList.contains('js-hapus-indikator')) {
                    konfirmasiHapus(url(routes.indikatorDestroy, t.dataset.id));
                }
            });

            restoreSelection();
        })();
    </script>
@endsection
