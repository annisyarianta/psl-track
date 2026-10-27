@extends('layouts.app')
@section('title')
    Kalender Task
@endsection

@section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h4 class="card-title mb-0">Kalender Task</h4>
                                    <a href="{{ route('task.index') }}" class="btn btn-light">
                                        <i class="bx bx-table me-1"></i> Tabel
                                    </a>
                                </div>

                                <div class="mb-3 small">
                                    <span class="badge" style="background:#74788d">To Do</span>
                                    <span class="badge" style="background:#50a5f1">In Progress</span>
                                    <span class="badge" style="background:#34c38f">Done</span>
                                </div>

                                <div id="kalender-task"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="modalDetailTask" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Detail Task</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <dl class="row mb-0">
                                    <dt class="col-sm-4">Program</dt>
                                    <dd class="col-sm-8" id="detail-program"></dd>
                                    <dt class="col-sm-4">Deskripsi</dt>
                                    <dd class="col-sm-8" id="detail-deskripsi"></dd>
                                    <dt class="col-sm-4">Tenggat</dt>
                                    <dd class="col-sm-8" id="detail-tenggat"></dd>
                                    <dt class="col-sm-4">Diberikan oleh</dt>
                                    <dd class="col-sm-8" id="detail-pemberi"></dd>
                                    <dt class="col-sm-4">Ditugaskan ke</dt>
                                    <dd class="col-sm-8" id="detail-penerima"></dd>
                                    <dt class="col-sm-4">Status</dt>
                                    <dd class="col-sm-8" id="detail-status"></dd>
                                </dl>
                            </div>
                            <div class="modal-footer">
                                <a href="#" id="tombol-edit-task" class="btn btn-warning d-none">Edit</a>
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
            const kontainerKalender = document.getElementById('kalender-task');
            const modalDetailTask = new bootstrap.Modal(document.getElementById('modalDetailTask'));
            const tombolEditTask = document.getElementById('tombol-edit-task');

            const kalenderTask = new FullCalendar.Calendar(kontainerKalender, {
                initialView: 'dayGridMonth',
                height: 'auto',
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
                events: "{{ route('task.kalender.data') }}",
                eventClick: function(infoEvent) {
                    const detailTask = infoEvent.event.extendedProps;

                    document.getElementById('detail-program').textContent = detailTask.namaProgram;
                    document.getElementById('detail-deskripsi').textContent = detailTask.deskripsiTugas;
                    document.getElementById('detail-tenggat').textContent = detailTask.tenggatWaktu;
                    document.getElementById('detail-pemberi').textContent = detailTask.pemberiTugas;
                    document.getElementById('detail-penerima').textContent = detailTask.penerimaTugas;
                    document.getElementById('detail-status').textContent = detailTask.labelStatus;

                    if (detailTask.urlEdit) {
                        tombolEditTask.href = detailTask.urlEdit;
                        tombolEditTask.classList.remove('d-none');
                    } else {
                        tombolEditTask.classList.add('d-none');
                    }

                    modalDetailTask.show();
                }
            });

            kalenderTask.render();
        });
    </script>
@endsection
