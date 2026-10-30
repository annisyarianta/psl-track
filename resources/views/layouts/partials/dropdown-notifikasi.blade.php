<div class="dropdown d-inline-block" id="dropdownNotifikasi">
    <button type="button" class="btn header-item noti-icon position-relative" id="tombolLoncengNotifikasi"
        data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="bx bx-bell icon-lg"></i>
        <span class="badge bg-danger rounded-pill d-none" id="badgeJumlahNotifikasi">0</span>
    </button>

    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="tombolLoncengNotifikasi">
        <div class="p-3">
            <div class="row align-items-center">
                <div class="col">
                    <h6 class="m-0">Notifikasi</h6>
                </div>
                <div class="col-auto">
                    <a href="javascript:void(0)" id="tombolTandaiSemuaDibaca"
                        class="small text-reset text-decoration-underline">
                        Tandai semua dibaca
                    </a>
                </div>
            </div>
        </div>

        <div data-simplebar style="max-height: 300px;">
            <div id="wadahDaftarNotifikasi">
                <div class="text-center text-muted py-4">Memuat notifikasi...</div>
            </div>
        </div>

        <div class="p-2 border-top d-grid">
            <a class="btn btn-sm btn-link font-size-14 text-center" href="{{ route('task.index') }}">
                <i class="mdi mdi-arrow-right-circle me-1"></i>
                <span>Lihat Task Tracker</span>
            </a>
        </div>
    </div>
</div>

<style>
    .notifikasi-belum-dibaca {
        background-color: rgba(var(--bs-primary-rgb), 0.08);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlDaftarNotifikasi = @json(route('notifikasi.daftar'));
        const urlTandaiSemuaDibaca = @json(route('notifikasi.tandai-semua-dibaca'));
        const tokenCsrf = @json(csrf_token());
        const intervalPembaruanMilidetik = 60000;

        const dropdownNotifikasi = document.getElementById('dropdownNotifikasi');
        const badgeJumlahNotifikasi = document.getElementById('badgeJumlahNotifikasi');
        const wadahDaftarNotifikasi = document.getElementById('wadahDaftarNotifikasi');
        const tombolTandaiSemuaDibaca = document.getElementById('tombolTandaiSemuaDibaca');

        function perbaruiBadge(jumlahBelumDibaca) {
            if (jumlahBelumDibaca > 0) {
                badgeJumlahNotifikasi.textContent = jumlahBelumDibaca > 99 ? '99+' : jumlahBelumDibaca;
                badgeJumlahNotifikasi.classList.remove('d-none');
            } else {
                badgeJumlahNotifikasi.classList.add('d-none');
            }
        }

        function buatItemNotifikasi(notifikasi) {
            const tautan = document.createElement('a');
            tautan.href = notifikasi.url_buka;
            tautan.className = 'text-reset notification-item d-block px-3 py-2' +
                (notifikasi.sudah_dibaca ? '' : ' notifikasi-belum-dibaca');

            const baris = document.createElement('div');
            baris.className = 'd-flex';

            const wadahIkon = document.createElement('div');
            wadahIkon.className = 'avatar-xs me-3 flex-shrink-0';
            const lingkaranIkon = document.createElement('span');
            lingkaranIkon.className = 'avatar-title rounded-circle font-size-16 ' + notifikasi.warna;
            const ikon = document.createElement('i');
            ikon.className = notifikasi.ikon;
            lingkaranIkon.appendChild(ikon);
            wadahIkon.appendChild(lingkaranIkon);

            const wadahTeks = document.createElement('div');
            wadahTeks.className = 'flex-grow-1';
            const pesan = document.createElement('p');
            pesan.className = 'mb-1' + (notifikasi.sudah_dibaca ? ' text-muted' : ' fw-semibold');
            pesan.textContent = notifikasi.pesan_notifikasi;
            const waktu = document.createElement('p');
            waktu.className = 'mb-0 font-size-13 text-muted';
            waktu.textContent = notifikasi.waktu_relatif;
            wadahTeks.appendChild(pesan);
            wadahTeks.appendChild(waktu);

            baris.appendChild(wadahIkon);
            baris.appendChild(wadahTeks);
            tautan.appendChild(baris);

            return tautan;
        }

        function tampilkanDaftar(daftarNotifikasi) {
            wadahDaftarNotifikasi.replaceChildren();

            if (daftarNotifikasi.length === 0) {
                const pesanKosong = document.createElement('div');
                pesanKosong.className = 'text-center text-muted py-4';
                pesanKosong.textContent = 'Tidak ada notifikasi';
                wadahDaftarNotifikasi.appendChild(pesanKosong);
                return;
            }

            daftarNotifikasi.forEach(function(notifikasi) {
                wadahDaftarNotifikasi.appendChild(buatItemNotifikasi(notifikasi));
            });
        }

        function muatNotifikasi() {
            fetch(urlDaftarNotifikasi, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                })
                .then(function(respons) {
                    return respons.json();
                })
                .then(function(data) {
                    perbaruiBadge(data.jumlah_belum_dibaca);
                    tampilkanDaftar(data.daftar_notifikasi);
                })
                .catch(function(kesalahan) {
                    console.error('Gagal memuat notifikasi:', kesalahan);
                });
        }

        tombolTandaiSemuaDibaca.addEventListener('click', function() {
            fetch(urlTandaiSemuaDibaca, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': tokenCsrf,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                })
                .then(function() {
                    muatNotifikasi();
                })
                .catch(function(kesalahan) {
                    console.error('Gagal menandai semua notifikasi:', kesalahan);
                });
        });

        dropdownNotifikasi.addEventListener('show.bs.dropdown', muatNotifikasi);

        muatNotifikasi();
        setInterval(function() {
            if (!document.hidden) {
                muatNotifikasi();
            }
        }, intervalPembaruanMilidetik);
    });
</script>
