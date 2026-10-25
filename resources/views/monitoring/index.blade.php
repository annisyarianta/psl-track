@extends('layouts.app')

@section('title')
    Monitoring | PSL Track
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

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">Monitoring Triwulan</h3>
                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">KPI Tracker</li>
                                    <li class="breadcrumb-item active">Monitoring</li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                <form method="GET" action="{{ route('monitoring.index') }}">
                    <div class="card">
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="filterTahun" class="form-label">Tahun</label>
                                    <select id="filterTahun" name="id_tahun" class="form-select"
                                        onchange="this.form.submit()">
                                        @foreach ($daftarTahun as $tahun)
                                            <option value="{{ $tahun->id_tahun }}" @selected($tahun->id_tahun == $tahunDipilih->id_tahun)>
                                                {{ $tahun->tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="filterTriwulan" class="form-label">Triwulan</label>
                                    <select id="filterTriwulan" name="id_periode_tw" class="form-select"
                                        onchange="this.form.submit()">
                                        @foreach ($daftarPeriodeTriwulan as $periodeTriwulan)
                                            <option value="{{ $periodeTriwulan->id_periode_tw }}"
                                                @selected($periodeTriwulan->id_periode_tw == $periodeDipilih->id_periode_tw)>
                                                Triwulan {{ $periodeTriwulan->triwulan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="filterStatus" class="form-label">Status</label>
                                    <select id="filterStatus" name="status" class="form-select"
                                        onchange="this.form.submit()">
                                        <option value="">Semua status</option>
                                        <option value="notstarted" @selected($statusDipilih === 'notstarted')>Belum mulai</option>
                                        <option value="onprogress" @selected($statusDipilih === 'onprogress')>On Progress</option>
                                        <option value="done" @selected($statusDipilih === 'done')>Selesai</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="row">
                    @foreach ([
            'notstarted' => 'Belum Mulai',
            'onprogress' => 'On Progress',
            'done' => 'Selesai',
        ] as $kodeStatus => $labelRingkasan)
                        <div class="col-md-4">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <p class="text-muted mb-1">{{ $labelRingkasan }}</p>
                                    <h4 class="mb-0">{{ $ringkasanStatus[$kodeStatus] ?? 0 }}</h4>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                @php
                    $tampilanStatus = [
                        'notstarted' => ['label' => 'Belum Mulai', 'warna' => 'secondary'],
                        'onprogress' => ['label' => 'On Progress', 'warna' => 'primary'],
                        'done' => ['label' => 'Selesai', 'warna' => 'success'],
                    ];
                @endphp

                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover align-middle">
                                <thead class="align-middle">
                                    <tr>
                                        <th class="text-center w-25">Sub Kegiatan</th>
                                        <th class="text-center w-25">Indikator Sub Kegiatan</th>
                                        <th class="text-center">Target</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Update Terakhir</th>
                                        <th class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($daftarMonitoring as $monitoring)
                                        @php
                                            $indikatorSubKegiatan = $monitoring->indikatorSubKegiatan;
                                            $sudahDiisi = filled($monitoring->capaian);
                                        @endphp
                                        <tr>
                                            <td>
                                                {{ $indikatorSubKegiatan->subKegiatan->judul_sub_kegiatan }}
                                            </td>
                                            <td>
                                                {{ $indikatorSubKegiatan->nama_indikator_sub_kegiatan }}
                                            </td>
                                            <td class="text-center">{{ $indikatorSubKegiatan->target_staff ?? '-' }}</td>
                                            <td class="text-center">
                                                <span
                                                    class="badge badge-soft-{{ $tampilanStatus[$monitoring->status]['warna'] }}">
                                                    {{ $tampilanStatus[$monitoring->status]['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                @if ($sudahDiisi)
                                                    {{ $monitoring->last_updated_at->locale('id')->translatedFormat('d M Y H:i') }}
                                                    <br><small class="text-muted">oleh
                                                        {{ $monitoring->lastUpdatedBy->nama }}</small>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td class="align-middle">
                                                <div class="d-flex justify-content-center align-items-center gap-1">
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary tombol-detail-monitoring"
                                                        data-url="{{ route('monitoring.show', $monitoring) }}"
                                                        data-bs-toggle="modal" data-bs-target="#modalDetailMonitoring">
                                                        Detail
                                                    </button>
                                                    @if ($dapatMengisi)
                                                        <a href="{{ route('monitoring.edit', $monitoring) }}"
                                                            class="btn btn-sm {{ $sudahDiisi ? 'btn-outline-primary' : 'btn-primary' }}">
                                                            {{ $sudahDiisi ? 'Ubah' : 'Isi' }}
                                                        </a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum terdapat indikator pada
                                                periode ini.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            <div class="mt-4 mb-0">
                                {{ $daftarMonitoring->links() }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Detail --}}
                <div class="modal fade" id="modalDetailMonitoring" tabindex="-1"
                    aria-labelledby="judulModalDetailMonitoring" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title" id="judulModalDetailMonitoring">Detail Monitoring</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Tutup"></button>
                            </div>
                            <div class="modal-body">
                                <p id="pesanMemuatDetail" class="text-muted text-center mb-0">Memuat data...</p>

                                <div id="kontenDetailMonitoring" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <p class="text-muted mb-1">Program</p>
                                            <h6 id="detailProgram"></h6>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <p class="text-muted mb-1">Indikator program</p>
                                            <h6 id="detailIndikatorProgram"></h6>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <p class="text-muted mb-1">Target</p>
                                            <h6 id="detailTargetManager"></h6>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <p class="text-muted mb-1">Aspek</p>
                                            <h6 id="detailAspek"></h6>
                                        </div>
                                    </div>

                                    <hr>

                                    <p class="text-muted mb-1">Sub kegiatan</p>
                                    <h6 id="detailJudulSubKegiatan"></h6>
                                    <div id="detailIsiSubKegiatan" class="mb-3"></div>

                                    <p class="text-muted mb-1">Indikator Sub Kegiatan</p>
                                    <h6 id="detailIndikatorSubKegiatan"></h6>

                                    <hr>

                                    <p class="text-muted mb-1">Upaya</p>
                                    <div id="detailUpaya" class="mb-3"></div>

                                    <p class="text-muted mb-1">Capaian</p>
                                    <div id="detailCapaian" class="mb-3"></div>

                                    <p class="text-muted mb-1">Identifikasi</p>
                                    <div id="detailIdentifikasi" class="mb-3"></div>

                                    <p class="text-muted mb-1">Keterangan</p>
                                    <div id="detailKeterangan" class="mb-3"></div>

                                    <p class="text-muted mb-1">File Pelaporan</p>
                                    <div id="detailDaftarFile" class="list-group"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modalDetailMonitoring = document.getElementById('modalDetailMonitoring');
            const pesanMemuatDetail = document.getElementById('pesanMemuatDetail');
            const kontenDetailMonitoring = document.getElementById('kontenDetailMonitoring');

            function isiTeks(idElemen, teks) {
                document.getElementById(idElemen).textContent = teks || '-';
            }

            // Satu baris pada teks = satu poin.
            function isiDaftarPoin(idElemen, teks) {
                const wadahPoin = document.getElementById(idElemen);
                wadahPoin.innerHTML = '';

                const daftarBaris = (teks || '')
                    .split(/\r?\n/)
                    .map(function(baris) {
                        return baris.trim();
                    })
                    .filter(function(baris) {
                        return baris !== '';
                    });

                if (daftarBaris.length === 0) {
                    wadahPoin.textContent = '-';
                    return;
                }

                const daftarPoin = document.createElement('ul');
                daftarPoin.className = 'ps-3 mb-0';

                daftarBaris.forEach(function(baris) {
                    const itemPoin = document.createElement('li');
                    itemPoin.textContent = baris;
                    daftarPoin.appendChild(itemPoin);
                });

                wadahPoin.appendChild(daftarPoin);
            }

            function isiDaftarFile(daftarFile) {
                const wadahFile = document.getElementById('detailDaftarFile');
                wadahFile.innerHTML = '';

                if (daftarFile.length === 0) {
                    const pesanKosong = document.createElement('div');
                    pesanKosong.className = 'list-group-item text-muted';
                    pesanKosong.textContent = 'Belum ada file pelaporan.';
                    wadahFile.appendChild(pesanKosong);
                    return;
                }

                daftarFile.forEach(function(file) {
                    const tautanFile = document.createElement('a');
                    tautanFile.href = file.url_file;
                    tautanFile.target = '_blank';
                    tautanFile.rel = 'noopener';
                    tautanFile.className = 'list-group-item list-group-item-action';
                    tautanFile.textContent = file.nama_file;
                    wadahFile.appendChild(tautanFile);
                });
            }

            modalDetailMonitoring.addEventListener('show.bs.modal', async function(event) {
                const urlDetailMonitoring = event.relatedTarget.dataset.url;

                kontenDetailMonitoring.classList.add('d-none');
                pesanMemuatDetail.classList.remove('d-none');
                pesanMemuatDetail.textContent = 'Memuat data...';

                try {
                    const response = await fetch(urlDetailMonitoring, {
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                    });

                    if (!response.ok) {
                        throw new Error('Gagal memuat detail.');
                    }

                    const detailMonitoring = await response.json();

                    isiTeks('detailProgram', detailMonitoring.program);
                    isiTeks('detailIndikatorProgram', detailMonitoring.indikator_program);
                    isiTeks('detailTargetManager', detailMonitoring.target_manager);
                    isiTeks('detailAspek', detailMonitoring.aspek);
                    isiTeks('detailJudulSubKegiatan', detailMonitoring.judul_sub_kegiatan);
                    isiDaftarPoin('detailIsiSubKegiatan', detailMonitoring.isi_sub_kegiatan);
                    isiTeks('detailIndikatorSubKegiatan', detailMonitoring.indikator_sub_kegiatan);
                    isiDaftarPoin('detailUpaya', detailMonitoring.upaya);
                    isiDaftarPoin('detailCapaian', detailMonitoring.capaian);
                    isiDaftarPoin('detailIdentifikasi', detailMonitoring.identifikasi);
                    isiDaftarPoin('detailKeterangan', detailMonitoring.keterangan);
                    isiDaftarFile(detailMonitoring.daftar_file);

                    pesanMemuatDetail.classList.add('d-none');
                    kontenDetailMonitoring.classList.remove('d-none');
                } catch (error) {
                    pesanMemuatDetail.textContent =
                        'Detail monitoring gagal dimuat. Silakan coba lagi.';
                }
            });
        });
    </script>
@endsection
