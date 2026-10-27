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

    <style>
        #kalender-task {
            --fc-button-bg-color: var(--bs-primary);
            --fc-button-border-color: var(--bs-primary);
            --fc-button-hover-bg-color: color-mix(in srgb, var(--bs-primary) 85%, black);
            --fc-button-hover-border-color: color-mix(in srgb, var(--bs-primary) 85%, black);
            --fc-button-active-bg-color: color-mix(in srgb, var(--bs-primary) 75%, black);
            --fc-button-active-border-color: color-mix(in srgb, var(--bs-primary) 75%, black);
        }
    
        #kalender-task .fc-toolbar-title { font-size: 1.1rem; font-weight: 700; }
        #kalender-task .fc-button { padding: .25rem .6rem; font-size: .8rem; }
        #kalender-task .fc-event { font-size: .75rem; cursor: pointer; }
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
                            @if ($bolehMemberiTugas)
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h5 class="card-title mb-0 font-size-22">List Task</h5>
                                    <a href="{{ route('task.create') }}" class="btn btn-primary waves-effect btn-label waves-light">
                                        <i class="bx bx-plus label-icon"></i> Tambah Task
                                    </a>
                                </div>
                            @endif
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
                                                    $bolehUbahStatus =
                                                        $apakahPemberiTugas ||
                                                        $task->penerimaTugas->contains('id_user', $idUserLogin);
                                                @endphp
                                                <tr class="align-middle">
                                                    <td class="text-center">{{ $loop->iteration }}</td>
                                                    <td>{{ $task->judul_tugas }}
                                                    </td>
                                                    <td
                                                        class="{{ $terlambat ? 'text-danger' : '' }} text-center">
                                                        {{ $task->tenggat_waktu->format('d M Y') }}
                                                    </td>
                                                    <td class="text-center" style="min-width: 140px;">
                                                        @if ($bolehUbahStatus)
                                                            <form action="{{ route('task.status', $task->id_task) }}"
                                                                method="POST">
                                                                @csrf
                                                                @method('PATCH')
                                                                <select name="status" class="form-select form-select-sm"
                                                                    onchange="this.form.submit()">
                                                                    @foreach ($daftarStatus as $nilaiStatus => $labelStatus)
                                                                        <option value="{{ $nilaiStatus }}"
                                                                            {{ $task->status === $nilaiStatus ? 'selected' : '' }}>
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
                                                            class="btn btn-sm btn-outline-secondary tombol-detail-task" title="Detail"
                                                            data-detail="{{ json_encode($daftarDetailTask[$task->id_task]) }}">
                                                            <i class="bx bx-info-circle"></i>
                                                        </button>

                                                        @if ($apakahPemberiTugas)
                                                            <a href="{{ route('task.edit', $task->id_task) }}"
                                                                class="btn btn-sm btn-outline-warning" title="Edit"><i class="bx bxs-edit"></i></a>
                                                            <form action="{{ route('task.destroy', $task->id_task) }}"
                                                                method="POST" class="d-inline"
                                                                onsubmit="return confirm('Hapus task ini?')">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit"
                                                                    class="btn btn-sm btn-outline-danger" title="Hapus"><i class="bx bx-trash"></i></button>
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
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

                // Tombol edit hanya muncul untuk pemberi tugas
                if (detailTask.urlEdit) {
                    tombolEditDariModal.href = detailTask.urlEdit;
                    tombolEditDariModal.classList.remove('d-none');
                } else {
                    tombolEditDariModal.classList.add('d-none');
                }

                modalDetailTask.show();
            }

            // Tabel
            $('#datatable').DataTable();

            $('#datatable').on('click', '.tombol-detail-task', function() {
                tampilkanDetailTask(JSON.parse(this.dataset.detail));
            });

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
        });
    </script>
@endsection
