@extends('layouts.app')

@section('title')
    Task Tracker | PSL Track
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

    @if ($errors->any())
        <script>
            alertify.error(@json($errors->first()));
        </script>
    @endif

    <style>
        #kalender-task {
            --fc-button-bg-color: var(--bs-primary);
            --fc-button-border-color: var(--bs-primary);
            --fc-button-hover-bg-color: color-mix(in srgb, var(--bs-primary) 85%, black);
            --fc-button-hover-border-color: color-mix(in srgb, var(--bs-primary) 85%, black);
            --fc-button-active-bg-color: color-mix(in srgb, var(--bs-primary) 75%, black);
            --fc-button-active-border-color: color-mix(in srgb, var(--bs-primary) 75%, black);
        }

        #kalender-task .fc-toolbar-title {
            font-size: 1.1rem;
            font-weight: 700;
        }

        #kalender-task .fc-button {
            padding: .25rem .6rem;
            font-size: .8rem;
        }

        #kalender-task .fc-event {
            font-size: .75rem;
            cursor: pointer;
        }
    </style>

    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                            <h3 class="mb-sm-0">
                                Task Tracker
                            </h3>

                            <div class="page-title-right">
                                <ol class="breadcrumb m-0">
                                    <li class="breadcrumb-item">
                                        <a href="javascript: void(0);">PSL Track</a>
                                    </li>
                                    <li class="breadcrumb-item active">
                                        <a href="{{ route('task.index') }}">Task Tracker</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kalender --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title mb-0">Kalender Task</h4>
                                <div class="small">
                                    <span class="badge" style="background:#74788d">To Do</span>
                                    <span class="badge" style="background:#50a5f1">In Progress</span>
                                    <span class="badge" style="background:#f06548">Revisi</span>
                                    <span class="badge" style="background:#34c38f">Done</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div id="kalender-task"></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header d-flex flex-wrap align-items-center justify-content-between gap-2">
                                <h5 class="card-title mb-0 font-size-22">List Task</h5>
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <ul class="nav nav-pills" id="filter-status-task">
                                        <li class="nav-item"><a class="nav-link active" href="#"
                                                data-status="">Semua</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#" data-status="todo">To
                                                Do</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#" data-status="inprogress">In
                                                Progress</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#"
                                                data-status="revisi">Revisi</a></li>
                                        <li class="nav-item"><a class="nav-link" href="#" data-status="done">Done</a>
                                        </li>
                                    </ul>
                                    @if ($bolehMemberiTugas)
                                        <a href="{{ route('task.create') }}"
                                            class="btn btn-primary waves-effect btn-label waves-light">
                                            <i class="bx bx-plus label-icon"></i> Tambah Task
                                        </a>
                                    @endif
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table id="datatable" class="table table-bordered align-middle w-100 table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center" style="width: 5%">No.</th>
                                                <th class="text-center" style="width: 45%">Judul Tugas</th>
                                                <th class="text-center" style="width: 15%">Tenggat Waktu</th>
                                                <th class="text-center" style="width: 15%">Status</th>
                                                <th class="text-center" style="width: 20%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($daftarTask as $task)
                                                @php
                                                    $terlambat =
                                                        $task->status !== 'done' && $task->tenggat_waktu->lt(today());
                                                    $apakahPemberiTugas = (int) $task->assigned_by === $idUserLogin;
                                                    $bolehMemperbaruiTask =
                                                        $apakahPemberiTugas ||
                                                        $task->penerimaTugas->contains('id_user', $idUserLogin);
                                                @endphp
                                                <tr class="align-middle">
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $task->judul_tugas }}
                                                    </td>
                                                    <td class="{{ $terlambat ? 'text-danger' : '' }} text-center">
                                                        {{ $task->tenggat_waktu->format('d M Y') }}
                                                    </td>
                                                    <td class="text-center" style="min-width: 140px;"
                                                        data-search="{{ $task->status }}">
                                                        @if ($bolehMemperbaruiTask)
                                                            <form action="{{ route('task.status', $task->id_task) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <select name="status" class="form-select form-select-sm"
                                                                    data-status-awal="{{ $task->status }}"
                                                                    onchange="ubahStatusTask(this)">
                                                                    @foreach ($daftarStatus as $nilaiStatus => $labelStatus)
                                                                        <option value="{{ $nilaiStatus }}"
                                                                            {{ $task->status === $nilaiStatus ? 'selected' : '' }}
                                                                            {{ $nilaiStatus === 'revisi' && !$apakahPemberiTugas ? 'disabled' : '' }}>
                                                                            {{ $labelStatus }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </form>
                                                        @else
                                                            <span
                                                                class="badge rounded-pill {{ \App\Models\Task::WARNA_BADGE_STATUS[$task->status] }}">
                                                                {{ $daftarStatus[$task->status] }}
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="text-nowrap text-center">
                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-secondary tombol-detail-task"
                                                            title="Detail"
                                                            data-detail="{{ json_encode($daftarDetailTask[$task->id_task]) }}">
                                                            <i class="bx bx-info-circle"></i>
                                                        </button>

                                                        @if ($bolehMemperbaruiTask)
                                                            <a href="{{ route('task.edit', $task->id_task) }}"
                                                                class="btn btn-sm btn-outline-warning" title="Edit">
                                                                <i class="bx bxs-edit"></i>
                                                            </a>
                                                        @endif

                                                        @if ($apakahPemberiTugas)
                                                            <form action="{{ route('task.destroy', $task->id_task) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Hapus task ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger" title="Hapus">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Detail Task (dipakai bersama oleh tabel dan kalender) --}}
                <div class="modal fade" id="modalDetailTask" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-scrollable modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title">Detail Task</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body card-body">
                                <div class="table-responsive">
                                    <table class="table table-striped mb-0">
                                        <tbody>
                                            <tr>
                                                <th scope="row" style="width: 230px">Program</th>
                                                <td id="detail-program"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Judul</th>
                                                <td id="detail-judul"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Deskripsi</th>
                                                <td id="detail-deskripsi" style="white-space: pre-line;"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Tenggat Waktu</th>
                                                <td id="detail-tenggat"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Diberikan oleh</th>
                                                <td id="detail-pemberi"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Ditugaskan ke</th>
                                                <td id="detail-daftar-penerima"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Status</th>
                                                <td id="detail-status"></td>
                                            </tr>
                                            <tr id="baris-keterangan-revisi" class="d-none">
                                                <th scope="row">Keterangan Revisi</th>
                                                <td id="detail-keterangan-revisi" style="white-space: pre-line;"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">Hasil Tugas</th>
                                                <td id="detail-hasil" style="white-space: pre-line;"></td>
                                            </tr>
                                            <tr>
                                                <th scope="row">File</th>
                                                <td id="detail-daftar-file"></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <a href="#" id="tombol-edit-dari-modal" class="btn btn-warning d-none">Edit</a>
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Revisi (untuk ubah status langsung di tabel) --}}
                <div class="modal fade" id="modalRevisiTask" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" id="form-revisi-task" class="modal-content">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="revisi">
                            <div class="modal-header">
                                <h4 class="modal-title">Revisi Task</h4>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label for="keterangan-revisi-modal" class="form-label">Keterangan Revisi</label>
                                <textarea name="keterangan_revisi" id="keterangan-revisi-modal" rows="4" class="form-control"
                                    placeholder="Tuliskan bagian yang perlu direvisi" required></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-warning">Kirim Revisi</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const daftarDetailTask = @json($daftarDetailTask);
            const modalDetailTask = new bootstrap.Modal(document.getElementById('modalDetailTask'));
            const kontainerDaftarPenerima = document.getElementById('detail-daftar-penerima');
            const kontainerDaftarFile = document.getElementById('detail-daftar-file');
            const tombolEditDariModal = document.getElementById('tombol-edit-dari-modal');

            function tampilkanDetailTask(detailTask) {
                document.getElementById('detail-program').textContent = detailTask.namaProgram;
                document.getElementById('detail-judul').textContent = detailTask.judulTugas;
                document.getElementById('detail-deskripsi').textContent = detailTask.deskripsiTugas;
                document.getElementById('detail-tenggat').textContent = detailTask.tenggatWaktu;
                document.getElementById('detail-pemberi').textContent = detailTask.pemberiTugas;
                document.getElementById('detail-status').textContent = detailTask.labelStatus;
                document.getElementById('detail-keterangan-revisi').textContent = detailTask.keteranganRevisi || '';
                document.getElementById('baris-keterangan-revisi')
                    .classList.toggle('d-none', !detailTask.keteranganRevisi);
                document.getElementById('detail-hasil').textContent = detailTask.hasilTugas;

                kontainerDaftarPenerima.innerHTML = '';
                detailTask.daftarPenerima.forEach(function(namaPenerima) {
                    const badgePenerima = document.createElement('span');
                    badgePenerima.className = 'badge bg-light text-dark me-1';
                    badgePenerima.textContent = namaPenerima;
                    kontainerDaftarPenerima.appendChild(badgePenerima);
                });

                kontainerDaftarFile.innerHTML = '';
                if (detailTask.daftarFile.length === 0) {
                    kontainerDaftarFile.textContent = '-';
                } else {
                    detailTask.daftarFile.forEach(function(fileTask) {
                        const tautanFile = document.createElement('a');
                        tautanFile.href = fileTask.urlFile;
                        tautanFile.target = '_blank';
                        tautanFile.className = 'd-block';
                        tautanFile.textContent = fileTask.namaFile;
                        kontainerDaftarFile.appendChild(tautanFile);
                    });
                }

                if (detailTask.urlEdit) {
                    tombolEditDariModal.href = detailTask.urlEdit;
                    tombolEditDariModal.classList.remove('d-none');
                } else {
                    tombolEditDariModal.classList.add('d-none');
                }

                modalDetailTask.show();
            }

            // Tabel
            const tabelTask = $('#datatable').DataTable();

            $('#datatable').on('click', '.tombol-detail-task', function() {
                tampilkanDetailTask(JSON.parse(this.dataset.detail));
            });

            // Filter status
            $('#filter-status-task .nav-link').on('click', function(event) {
                event.preventDefault();
                const statusDipilih = $(this).data('status');
                $('#filter-status-task .nav-link').removeClass('active');
                $(this).addClass('active');
                tabelTask
                    .column(3)
                    .search(statusDipilih ? '^' + statusDipilih + '$' : '', true, false)
                    .draw();
            });

            tabelTask.on('order.dt search.dt draw.dt', function() {
                tabelTask.column(0, {
                    search: 'applied',
                    order: 'applied'
                }).nodes().each(function(kolomNomor, urutan) {
                    kolomNomor.innerHTML = urutan + 1;
                });
            }).draw();

            // Kalender
            const kalenderTask = new FullCalendar.Calendar(document.getElementById('kalender-task'), {
                initialView: 'dayGridMonth',
                height: 480,
                fixedWeekCount: false,
                dayMaxEvents: 2,
                views: {
                    dayGridWeek: {
                        dayMaxEvents: false
                    },
                    dayGridDay: {
                        dayMaxEvents: false
                    }
                },
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,dayGridWeek,dayGridDay'
                },
                buttonText: {
                    today: 'Today',
                    month: 'Month',
                    week: 'Week',
                    day: 'Day'
                },
                events: @json($daftarEventKalender),
                eventDidMount: function(infoEvent) {
                    infoEvent.el.title = infoEvent.event.extendedProps.deskripsiTugas;
                },
                eventClick: function(infoEvent) {
                    tampilkanDetailTask(infoEvent.event.extendedProps);
                }
            });

            kalenderTask.render();

            const parameterUrl = new URLSearchParams(window.location.search);
            const idTaskYangDibuka = parameterUrl.get('buka_detail');

            if (idTaskYangDibuka && daftarDetailTask[idTaskYangDibuka]) {
                tampilkanDetailTask(daftarDetailTask[idTaskYangDibuka]);
            }

            if (idTaskYangDibuka) {
                parameterUrl.delete('buka_detail');
                const sisaParameter = parameterUrl.toString();
                window.history.replaceState({},
                    '',
                    window.location.pathname + (sisaParameter ? '?' + sisaParameter : '')
                );
            }
        });


        let selectStatusYangDiubah = null;
        let apakahFormRevisiDikirim = false;

        function ubahStatusTask(selectStatus) {
            if (selectStatus.value !== 'revisi') {
                selectStatus.form.submit();
                return;
            }

            const modalElemen = document.getElementById('modalRevisiTask');
            const formRevisiTask = document.getElementById('form-revisi-task');

            selectStatusYangDiubah = selectStatus;
            apakahFormRevisiDikirim = false;

            formRevisiTask.action = selectStatus.form.action;
            document.getElementById('keterangan-revisi-modal').value = '';

            bootstrap.Modal.getOrCreateInstance(modalElemen).show();
        }

        document.getElementById('form-revisi-task').addEventListener('submit', function() {
            apakahFormRevisiDikirim = true;
        });

        document.getElementById('modalRevisiTask').addEventListener('hidden.bs.modal', function() {
            if (!apakahFormRevisiDikirim && selectStatusYangDiubah) {
                selectStatusYangDiubah.value = selectStatusYangDiubah.dataset.statusAwal;
            }
        });
    </script>
@endsection
