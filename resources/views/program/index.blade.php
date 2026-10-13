{{--
|--------------------------------------------------------------------------
| MANAGER : Program & Indikator Program
| resources/views/program/index.blade.php
|--------------------------------------------------------------------------
| Cari kata "SESUAIKAN" untuk menemukan bagian yang perlu Anda cek.
|
| DATA DARI CONTROLLER (index):
|   $sasaranInisiatif = SasaranInisiatif::with(['indikatorInisiatif' => function ($q) {
|       $q->withCount('program');
|   }])->get();
|   Kolom yang dipakai: nama_sasaran_inisiatif, id_indikator_inisiatif,
|   nama_indikator_inisiatif, target_dirbag, program_count
|
| ENDPOINT JSON (program.by-indikator-inisiatif), dipanggil saat indikator inisiatif diklik:
|   return response()->json(
|       Program::where('id_indikator_inisiatif', $id)
|           ->with(['indikatorProgram' => function ($q) { $q->withCount('kegiatan'); }])
|           ->get()
|   );
|   Bentuk JSON: [ { id_program, nama_program,
|     indikator_program: [ { id_indikator_program, nama_indikator_program,
|        target_manager, aspek, periode_pengukuran, kegiatan_count } ] } ]
|--------------------------------------------------------------------------
--}}

{{-- SESUAIKAN: nama layout & section mengikuti template Minia Anda --}}
@extends('layouts.master')

@section('title', 'Manager')

@section('content')

    @php
        // SESUAIKAN: Hak akses hanya untuk tampilan; tetap cek juga di controller/policy.
        // SESUAIKAN: data yang tampil sebaiknya dibatasi per unit user (lihat tabel plo_unit: id_indikator_program, id_unit) di controller.
        $canManage = auth()->user()->role === 'manager';
    @endphp

    <div class="row">

        {{-- JALUR TURUNAN --}}
        <div class="col-12 mb-3">
            <span class="text-muted small me-1">Jalur:</span>
            <span class="badge bg-light text-muted">Dir. Utama</span>
            <i class="mdi mdi-chevron-right text-muted"></i>
            <span class="badge bg-light text-muted">Dir. Bagian</span>
            <i class="mdi mdi-chevron-right text-muted"></i>
            <span class="badge bg-success">Manager</span>
        </div>

        {{-- PANEL KIRI: Indikator Inisiatif dari Dir. Bagian (hanya lihat) --}}
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Indikator Inisiatif</h5>
                        <p class="text-muted mb-0">Dari Dir. Bagian</p>
                    </div>
                    <span class="badge bg-light text-muted">Hanya lihat</span>
                </div>

                <div class="card-body p-0">
                    @forelse ($sasaranInisiatif as $si)
                        @if ($si->indikatorInisiatif->count())
                            <div class="px-3 pt-3 pb-1 text-muted small">{{ $si->nama_sasaran_inisiatif }}</div>

                            <div class="list-group list-group-flush">
                                @foreach ($si->indikatorInisiatif as $ind)
                                    <a href="#" class="list-group-item list-group-item-action js-parent"
                                        data-id="{{ $ind->id_indikator_inisiatif }}"
                                        data-nama="{{ $ind->nama_indikator_inisiatif }}">
                                        <div class="d-flex justify-content-between align-items-start gap-2">
                                            <div>
                                                <h6 class="mb-1">{{ $ind->nama_indikator_inisiatif }}</h6>
                                                <p class="text-muted small mb-0">Target : {{ $ind->target_dirbag ?? '-' }}
                                                </p>
                                            </div>
                                            @if ($ind->program_count > 0)
                                                <span class="badge bg-warning text-dark">{{ $ind->program_count }}
                                                    Program</span>
                                            @else
                                                <span class="badge bg-danger">Belum diturunkan</span>
                                            @endif
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @empty
                        <div class="text-center text-muted p-4">Belum terdapat Indikator Inisiatif dari Dir. Bagian.</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- PANEL KANAN: Program milik Manager --}}
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">Program</h5>
                        <p class="text-muted mb-0" id="subKanan">Turunan dari Indikator Inisiatif yang dipilih</p>
                    </div>

                    @if ($canManage)
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-success">Bisa kelola</span>
                            <button type="button" class="btn btn-primary btn-sm" id="btnTambahItem" disabled>
                                <i class="mdi mdi-plus"></i> Tambah
                            </button>
                        </div>
                    @else
                        <span class="badge bg-light text-muted">Hanya lihat</span>
                    @endif
                </div>

                <div class="card-body" id="panelKananBody">
                    <div class="text-center text-muted py-4">Pilih Indikator Inisiatif</div>
                </div>
            </div>
        </div>
    </div>

    @if ($canManage)
        {{-- MODAL PROGRAM (tambah & edit) --}}
        <div class="modal fade" id="modalItem" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                {{-- SESUAIKAN: route program.store (action diganti lewat JavaScript saat edit) --}}
                <form method="POST" id="formItem" class="modal-content" action="{{ route('program.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="itemMethod" value="POST">
                    <input type="hidden" name="id_indikator_inisiatif" id="itemParentId">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalItemTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Program</label>
                            <input type="text" name="nama_program" id="itemNama" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL INDIKATOR PROGRAM (tambah & edit) --}}
        <div class="modal fade" id="modalIndikator" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                {{-- SESUAIKAN: route indikator-program.store --}}
                <form method="POST" id="formIndikator" class="modal-content"
                    action="{{ route('indikator-program.store') }}">
                    @csrf
                    <input type="hidden" name="_method" id="indikatorMethod" value="POST">
                    <input type="hidden" name="id_program" id="indikatorItemId">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalIndikatorTitle"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Indikator Program</label>
                            <input type="text" name="nama_indikator_program" id="indikatorNama" class="form-control"
                                required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Target</label>
                            <input type="text" name="target_manager" id="indikatorTarget" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Aspek</label>
                            <select name="aspek" id="indikatorAspek" class="form-select" required>
                                <option value="kualitas">Kualitas</option>
                                <option value="kuantitas">Kuantitas</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Periode Pengukuran</label>
                            <select name="periode_pengukuran" id="indikatorPeriode" class="form-select" required>
                                <option value="triwulan">Triwulan</option>
                                <option value="semester">Semester</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Form tersembunyi untuk hapus (dipanggil setelah konfirmasi SweetAlert) --}}
        <form method="POST" id="formHapus" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endif

@endsection

{{-- SESUAIKAN: pastikan layout Anda punya @stack('scripts'), atau pindahkan isi script ke section yang Anda pakai --}}
@push('scripts')
    <script>
        (function() {
            const canManage = @json($canManage);

            // SESUAIKAN: nama route (penanda __ID__ diganti lewat JavaScript)
            const routes = {
                items: "{{ route('program.by-indikator-inisiatif', '__ID__') }}",
                itemUpdate: "{{ route('program.update', '__ID__') }}",
                itemDestroy: "{{ route('program.destroy', '__ID__') }}",
                itemStore: "{{ route('program.store') }}",
                indikatorUpdate: "{{ route('indikator-program.update', '__ID__') }}",
                indikatorDestroy: "{{ route('indikator-program.destroy', '__ID__') }}",
                indikatorStore: "{{ route('indikator-program.store') }}",
            };

            // Nama field pada JSON (sesuai kolom ERD & nama relasi Eloquent)
            const F = {
                itemId: 'id_program',
                itemNama: 'nama_program',
                rel: 'indikator_program', // SESUAIKAN: nama relasi di JSON (snake_case dari relasi indikatorProgram)
                indId: 'id_indikator_program',
                indNama: 'nama_indikator_program',
                indTarget: 'target_manager',
                child: 'kegiatan_count', // SESUAIKAN: hasil withCount('kegiatan') pada indikator program
            };
            const labelItem = 'Program';
            const labelIndikator = 'Indikator Program';
            const labelNext = 'Ass. Manager';
            const labelChild = 'kegiatan';

            const body = document.getElementById('panelKananBody');
            const subKanan = document.getElementById('subKanan');
            const btnTambah = document.getElementById('btnTambahItem');
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

            // ---------- Panel kiri ----------
            document.querySelectorAll('.js-parent').forEach((el) => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    selectParent(el);
                });
            });

            function selectParent(el) {
                document.querySelectorAll('.js-parent').forEach((x) => x.classList.remove('active'));
                el.classList.add('active');
                selectedId = el.dataset.id;
                selectedNama = el.dataset.nama;
                sessionStorage.setItem(storeKey, selectedId);
                subKanan.textContent = 'Turunan dari: ' + selectedNama;
                if (btnTambah) btnTambah.disabled = false;
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
                    const open = i === 0;
                    const inds = it[F.rel] || [];
                    const total = inds.reduce((a, n) => a + (n[F.child] || 0), 0);
                    const footer = total > 0 ?
                        'Diturunkan ke ' + labelNext + ': ' + total + ' ' + labelChild :
                        'Belum diturunkan ke ' + labelNext;
                    const id = it[F.itemId];

                    html += `
            <div class="accordion-item mb-2">
                <h2 class="accordion-header">
                    <button class="accordion-button ${open ? '' : 'collapsed'}" type="button"
                            data-bs-toggle="collapse" data-bs-target="#item${id}">
                        <span class="badge bg-primary me-2">${String(i + 1).padStart(2, '0')}</span>
                        <span class="flex-grow-1">${esc(it[F.itemNama])}</span>
                        <span class="text-muted small me-3">${inds.length} indikator</span>
                    </button>
                </h2>
                <div id="item${id}" class="accordion-collapse collapse ${open ? 'show' : ''}">
                    <div class="accordion-body">
                        ${canManage ? `
                            <div class="d-flex justify-content-end gap-1 mb-2">
                                <button class="btn btn-sm btn-light js-edit-item" data-id="${id}"><i class="mdi mdi-pencil"></i></button>
                                <button class="btn btn-sm btn-light js-hapus-item" data-id="${id}"><i class="mdi mdi-delete"></i></button>
                            </div>` : ''}

                        ${inds.map((n) => `
                            <div class="d-flex justify-content-between align-items-start border-bottom py-2">
                                <div>
                                    <div>${esc(n[F.indNama])}</div>
                                    <div class="text-muted small">Target : ${esc(n[F.indTarget] || '-')}</div>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-muted">${esc(cap(n.aspek))}</span>
                                        <span class="badge bg-light text-muted">${esc(cap(n.periode_pengukuran))}</span>
                                    </div>
                                </div>
                                ${canManage ? `
                            <div class="d-flex gap-1">
                                <button class="btn btn-sm btn-light js-edit-indikator" data-item="${id}" data-id="${n[F.indId]}"><i class="mdi mdi-pencil"></i></button>
                                <button class="btn btn-sm btn-light js-hapus-indikator" data-id="${n[F.indId]}"><i class="mdi mdi-delete"></i></button>
                            </div>` : ''}
                            </div>`).join('')}

                        ${canManage ? `
                            <button class="btn btn-link btn-sm px-0 mt-2 js-tambah-indikator" data-item="${id}">
                                <i class="mdi mdi-plus"></i> Tambah ${esc(labelIndikator)}
                            </button>` : ''}
                    </div>
                    <div class="px-3 pb-2 text-muted small"><i class="mdi mdi-arrow-down"></i> ${footer}</div>
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

            // ---------- Modal program ----------
            const modalItem = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalItem'));
            const formItem = document.getElementById('formItem');

            function bukaItem(judul, action, method, nama) {
                document.getElementById('modalItemTitle').textContent = judul;
                formItem.action = action;
                document.getElementById('itemMethod').value = method;
                document.getElementById('itemParentId').value = selectedId;
                document.getElementById('itemNama').value = nama || '';
                modalItem.show();
            }

            btnTambah.addEventListener('click', () => {
                bukaItem('Tambah ' + labelItem, routes.itemStore, 'POST', '');
            });

            // ---------- Modal indikator program ----------
            const modalInd = bootstrap.Modal.getOrCreateInstance(document.getElementById('modalIndikator'));
            const formInd = document.getElementById('formIndikator');

            function bukaIndikator(judul, action, method, itemId, n) {
                document.getElementById('modalIndikatorTitle').textContent = judul;
                formInd.action = action;
                document.getElementById('indikatorMethod').value = method;
                document.getElementById('indikatorItemId').value = itemId;
                document.getElementById('indikatorNama').value = n ? n[F.indNama] : '';
                document.getElementById('indikatorTarget').value = n ? (n[F.indTarget] || '') : '';
                document.getElementById('indikatorAspek').value = n ? n.aspek : 'kualitas';
                document.getElementById('indikatorPeriode').value = n ? n.periode_pengukuran : 'triwulan';
                modalInd.show();
            }

            // ---------- Hapus (SweetAlert) ----------
            function konfirmasiHapus(action) {
                Swal.fire({
                    title: 'Hapus data?',
                    icon: 'warning',
                    showCancelButton: true,
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

                if (t.classList.contains('js-edit-item')) {
                    const it = itemsCache.find((x) => String(x[F.itemId]) === t.dataset.id);
                    bukaItem('Edit ' + labelItem, url(routes.itemUpdate, t.dataset.id), 'PUT', it[F.itemNama]);
                }
                if (t.classList.contains('js-hapus-item')) {
                    konfirmasiHapus(url(routes.itemDestroy, t.dataset.id));
                }
                if (t.classList.contains('js-tambah-indikator')) {
                    bukaIndikator('Tambah ' + labelIndikator, routes.indikatorStore, 'POST', t.dataset.item,
                        null);
                }
                if (t.classList.contains('js-edit-indikator')) {
                    const it = itemsCache.find((x) => String(x[F.itemId]) === t.dataset.item);
                    const n = (it[F.rel] || []).find((x) => String(x[F.indId]) === t.dataset.id);
                    bukaIndikator('Edit ' + labelIndikator, url(routes.indikatorUpdate, t.dataset.id), 'PUT', t
                        .dataset.item, n);
                }
                if (t.classList.contains('js-hapus-indikator')) {
                    konfirmasiHapus(url(routes.indikatorDestroy, t.dataset.id));
                }
            });

            restoreSelection();
        })();
    </script>
@endpush
