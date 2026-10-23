@extends('layouts.app')

@section('title')
    Sasaran Inisiatif | PSL Track
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
                                    <li class="breadcrumb-item active">
                                        Dir. Bagian
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    {{-- PANEL KIRI: Indikator KPI dari Dir. Utama --}}
                    <div class="col-lg-5">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Indikator KPI</h5>
                                    <p class="text-muted mb-0">Dari Dir. Utama</p>
                                </div>
                            </div>

                            <div class="card-body p-0">
                                @forelse ($sasaranStrategis as $ss)
                                    @if ($ss->indikatorKpi->count())
                                        <div class="px-3 pt-3 pb-1 text-primary small">{{ $ss->nama_sasaran_strategis }}
                                        </div>
                                        <div class="list-group list-group-flush">
                                            @foreach ($ss->indikatorKpi as $kpi)
                                                <a href="#" class="list-group-item list-group-item-action js-parent"
                                                    data-id="{{ $kpi->id_indikator_kpi }}"
                                                    data-nama="{{ $kpi->nama_indikator_kpi }}">
                                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                                        <div>
                                                            <h6 class="mb-1">{{ $kpi->nama_indikator_kpi }}</h6>
                                                            <p class="text-muted small mb-0">Target :
                                                                {{ $kpi->target_dirut ?? '-' }}
                                                            </p>
                                                        </div>
                                                        <span class="badge bg-info">
                                                            {{ $kpi->sasaran_inisiatif_count ?? 0 }}
                                                            Sasaran Inisiatif
                                                        </span>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif
                                @empty
                                    <div class="text-center text-muted p-4">Belum terdapat Indikator KPI dari Dir. Utama.
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- PANEL KANAN: Sasaran Inisiatif milik Dir. Bagian --}}
                    <div class="col-lg-7">
                        <div class="card shadow-sm">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <div>
                                    <h5 class="card-title mb-0">Sasaran Inisiatif</h5>
                                    <p class="text-muted mb-0" id="subKanan">Turunan dari Indikator KPI yang dipilih</p>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" id="btnTambahItem" data-bs-toggle="modal"
                                        data-bs-target="#tambahSasaranInisiatif" class="btn btn-primary btn-sm" disabled>
                                        <i class="mdi mdi-plus"></i> Tambah
                                    </button>
                                </div>
                            </div>
                            <div class="card-body" id="panelKananBody">
                                <div class="text-center text-muted py-4">Pilih Indikator KPI</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- MODAL TAMBAH SASARAN INISIATIF --}}
                <div class="modal fade" id="tambahSasaranInisiatif" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">
                                    Tambah Sasaran Inisiatif
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('sasaran-inisiatif.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_indikator_kpi" id="inputIdIndikatorKpi"
                                    value="{{ old('id_indikator_kpi') }}">
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label for="nama_sasaran_inisiatif" class="col-sm-3 col-form-label">Nama Sasaran
                                            Inisiatif
                                            <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="nama_sasaran_inisiatif" name="nama_sasaran_inisiatif" rows="4" required>{{ old('nama_sasaran_inisiatif') }}</textarea>
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

                {{-- MODAL EDIT SASARAN INISIATIF --}}
                <div class="modal fade" id="editSasaranInisiatif" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="judulEditSasaranInisiatif" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="judulEditSasaranInisiatif">Edit Sasaran Inisiatif</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="formEditSasaranInisiatif" action="" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label for="edit_nama_sasaran_inisiatif" class="col-sm-3 col-form-label">Nama
                                            Sasaran
                                            Inisiatif <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="edit_nama_sasaran_inisiatif" name="nama_sasaran_inisiatif" rows="4"
                                                required></textarea>
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

                {{-- modal tambah indikator inisiatif --}}
                <div class="modal fade" id="tambahIndikatorInisiatif" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">
                                    Tambah Indikator Inisiatif
                                </h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form action="{{ route('indikator-inisiatif.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="id_sasaran_inisiatif" id="inputIdSasaranInisiatif"
                                    value="{{ old('id_sasaran_inisiatif') }}">
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label for="nama_indikator_inisiatif" class="col-sm-3 col-form-label">Nama
                                            Indikator Inisiatif
                                            <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="nama_indikator_inisiatif" name="nama_indikator_inisiatif" rows="4"
                                                required>{{ old('nama_indikator_inisiatif') }}</textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="target_dirbag" class="col-sm-3 col-form-label">Target
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="target_dirbag"
                                                name="target_dirbag" value="{{ old('target_dirbag') }}"></input>
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

                {{-- MODAL EDIT INDIKATOR INISIATIF --}}
                <div class="modal fade" id="editIndikatorInisiatif" data-bs-backdrop="static" data-bs-keyboard="false"
                    tabindex="-1" role="dialog" aria-labelledby="judulEditIndikatorInisiatif" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="judulEditIndikatorInisiatif">Edit Indikator Inisiatif</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <form id="formEditIndikatorInisiatif" action="" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="row mb-3">
                                        <label for="edit_nama_indikator_inisiatif" class="col-sm-3 col-form-label">Nama
                                            Indikator
                                            Inisiatif <span class="text-danger">*</span></label>
                                        <div class="col-sm-9">
                                            <textarea class="form-control" id="edit_nama_indikator_inisiatif" name="nama_indikator_inisiatif" rows="4"
                                                required></textarea>
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <label for="edit_target_dirbag" class="col-sm-3 col-form-label">Target</label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="edit_target_dirbag"
                                                name="target_dirbag">
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

    <script>
        (function() {
            const canManage = @json($canManage);

            const routes = {
                items: "{{ route('sasaran-inisiatif.by-kpi', '__ID__') }}",
                itemUpdate: "{{ route('sasaran-inisiatif.update', '__ID__') }}",
                itemDestroy: "{{ route('sasaran-inisiatif.destroy', '__ID__') }}",
                indikatorUpdate: "{{ route('indikator-inisiatif.update', '__ID__') }}",
                indikatorDestroy: "{{ route('indikator-inisiatif.destroy', '__ID__') }}",
            };

            const F = {
                itemId: 'id_sasaran_inisiatif',
                itemNama: 'nama_sasaran_inisiatif',
                rel: 'indikator_inisiatif',
                indId: 'id_indikator_inisiatif',
                indNama: 'nama_indikator_inisiatif',
                indTarget: 'target_dirbag',
                child: 'programs_count',
            };
            const labelItem = 'Sasaran Inisiatif';
            const labelIndikator = 'Indikator Inisiatif';

            const body = document.getElementById('panelKananBody');
            const subKanan = document.getElementById('subKanan');
            const btnTambah = document.getElementById('btnTambahItem');
            const modalTambahEl = document.getElementById('tambahSasaranInisiatif');
            const inputIdIndikatorKpi = document.getElementById('inputIdIndikatorKpi');
            const modalTambahIndEl = document.getElementById('tambahIndikatorInisiatif');
            const inputIdSasaranInisiatif = document.getElementById('inputIdSasaranInisiatif');
            const storeKey = 'kpi-tracker:sasaran-inisiatif';

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
                if (inputIdIndikatorKpi) inputIdIndikatorKpi.value = selectedId;
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
                    .then((r) => {
                        if (!r.ok) throw new Error('HTTP ' + r.status);
                        return r.json();
                    })
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
                                    ${inds.map((n) => `
                                                                        <div class="d-flex justify-content-between align-items-start border-bottom py-2">
                                                                            <div>
                                                                                <div>${esc(n[F.indNama])}</div>
                                                                                <div class="text-muted small">Target : ${esc(n[F.indTarget] || '-')}</div>
                                                                            </div>
                                                                            ${canManage ? `
                                                <div class="d-flex gap-1">
                                                    <button class="btn btn-sm btn-light js-edit-indikator" data-item="${id}" data-id="${n[F.indId]}"><i class="mdi mdi-pencil"></i></button>
                                                    <button class="btn btn-sm btn-light js-hapus-indikator" data-id="${n[F.indId]}"><i class="mdi mdi-delete"></i></button>
                                                </div>` : ''}
                                                                        </div>`).join('')}

                                    ${canManage ? `
                                                                        <button type="button" class="btn btn-link btn-sm px-0 mt-2"
                                                                                data-bs-toggle="modal" data-bs-target="#tambahIndikatorInisiatif"
                                                                                data-item="${id}">
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

            // ---------- Modal tambah sasaran inisiatif ----------
            if (modalTambahEl) {
                modalTambahEl.addEventListener('show.bs.modal', (e) => {
                    if (!selectedId) {
                        e.preventDefault();
                        return;
                    }
                    inputIdIndikatorKpi.value = selectedId;
                });

                modalTambahEl.addEventListener('hidden.bs.modal', () => {
                    document.getElementById('nama_sasaran_inisiatif').value = '';
                });
            }

            // ---------- Modal tambah indikator inisiatif ----------
            if (modalTambahIndEl) {
                modalTambahIndEl.addEventListener('show.bs.modal', (e) => {
                    const trigger = e.relatedTarget;
                    if (!trigger || !trigger.dataset.item) {
                        e.preventDefault();
                        return;
                    }
                    inputIdSasaranInisiatif.value = trigger.dataset.item;
                });

                modalTambahIndEl.addEventListener('hidden.bs.modal', () => {
                    document.getElementById('nama_indikator_inisiatif').value = '';
                    document.getElementById('target_dirbag').value = '';
                });
            }

            // ---------- Modal edit sasaran inisiatif ----------
            const modalEditSasaranInisiatif = document.getElementById('editSasaranInisiatif');
            const formEditSasaranInisiatif = document.getElementById('formEditSasaranInisiatif');
            const inputEditNamaSasaranInisiatif = document.getElementById('edit_nama_sasaran_inisiatif');

            function bukaModalEditSasaranInisiatif(sasaranInisiatif) {
                formEditSasaranInisiatif.action = url(routes.itemUpdate, sasaranInisiatif[F.itemId]);
                inputEditNamaSasaranInisiatif.value = sasaranInisiatif[F.itemNama];
                bootstrap.Modal.getOrCreateInstance(modalEditSasaranInisiatif).show();
            }

            // ---------- Modal edit indikator inisiatif ----------
            const modalEditIndikatorInisiatif = document.getElementById('editIndikatorInisiatif');
            const formEditIndikatorInisiatif = document.getElementById('formEditIndikatorInisiatif');
            const inputEditNamaIndikatorInisiatif = document.getElementById('edit_nama_indikator_inisiatif');
            const inputEditTargetIndikatorInisiatif = document.getElementById('edit_target_dirbag');

            function bukaModalEditIndikatorInisiatif(indikatorInisiatif) {
                formEditIndikatorInisiatif.action = url(routes.indikatorUpdate, indikatorInisiatif[F.indId]);
                inputEditNamaIndikatorInisiatif.value = indikatorInisiatif[F.indNama];
                inputEditTargetIndikatorInisiatif.value = indikatorInisiatif[F.indTarget] || '';
                bootstrap.Modal.getOrCreateInstance(modalEditIndikatorInisiatif).show();
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
                const t = e.target.closest('button');
                if (!t) return;

                if (t.classList.contains('js-edit-item')) {
                    const sasaranInisiatif = itemsCache.find((x) => String(x[F.itemId]) === t.dataset.id);
                    bukaModalEditSasaranInisiatif(sasaranInisiatif);
                }
                if (t.classList.contains('js-hapus-item')) {
                    konfirmasiHapus(url(routes.itemDestroy, t.dataset.id));
                }

                if (t.classList.contains('js-edit-indikator')) {
                    const sasaranInisiatif = itemsCache.find((x) => String(x[F.itemId]) === t.dataset.item);
                    const indikatorInisiatif = (sasaranInisiatif[F.rel] || []).find((x) => String(x[F
                        .indId]) === t.dataset.id);
                    bukaModalEditIndikatorInisiatif(indikatorInisiatif);
                }
                if (t.classList.contains('js-hapus-indikator')) {
                    konfirmasiHapus(url(routes.indikatorDestroy, t.dataset.id));
                }
            });

            restoreSelection();
        })();
    </script>
@endsection
