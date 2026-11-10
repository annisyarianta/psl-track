<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ProfilePasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;
use App\Http\Controllers\TahunController;
use App\Http\Controllers\UnitController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\SasaranStrategisController;
use App\Http\Controllers\IndikatorKpiController;
use App\Http\Controllers\SasaranInisiatifController;
use App\Http\Controllers\IndikatorInisiatifController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\IndikatorProgramController;
use App\Http\Controllers\KegiatanController;
use App\Http\Controllers\IndikatorKegiatanController;
use App\Http\Controllers\SubKegiatanController;
use App\Http\Controllers\IndikatorSubKegiatanController;
use App\Http\Controllers\PicUnitController;
use App\Http\Controllers\PicStaffController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\FilePelaporanController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\ExportLaporanController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [
        AuthController::class,
        'showLogin'
    ])->name('login');

    Route::post('/login', [
        AuthController::class,
        'login'
    ]);

    Route::get('/forgot-password', [
        ForgotPasswordController::class,
        'create'
    ])->name('password.request');

    Route::post('/forgot-password', [
        ForgotPasswordController::class,
        'sendResetLink'
    ])->name('password.email');

    Route::get('/reset-password/{token}', [
        ResetPasswordController::class,
        'create'
    ])->name('password.reset');

    Route::post('/reset-password', [
        ResetPasswordController::class,
        'reset'
    ])->name('password.update.reset');
});

Route::post('/logout', [
    AuthController::class,
    'logout'
])
    ->middleware('auth')
    ->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/password/change', [
        PasswordController::class,
        'edit'
    ])->name('password.change');

    Route::post('/password/change', [
        PasswordController::class,
        'update'
    ])->name('password.update');

    Route::get('/profil/ubah-password', [
        ProfilePasswordController::class,
        'showChangePasswordForm'
    ])->name('profile.password.edit');

    Route::put('/profil/ubah-password', [
        ProfilePasswordController::class,
        'updatePassword'
    ])->name('profile.password.update');
});


Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware([
        'auth',
        'force.password.change'
    ])
    ->name('dashboard');


Route::middleware([
    'auth',
    'force.password.change'
])->group(function () {

    /*
        |--------------------------------------------------------------------------
        | Route pemuat data (JSON) per menu KPI Tracker: akses lihat
        |--------------------------------------------------------------------------
        */
    Route::middleware('akses.menu:dir_bagian,lihat')->group(function () {
        Route::get(
            '/indikator-kpi/{id_indikator_kpi}/sasaran-inisiatif',
            [SasaranInisiatifController::class, 'byKpi']
        )->name('sasaran-inisiatif.by-kpi');
    });

    Route::middleware('akses.menu:manager,lihat')->group(function () {
        Route::get(
            '/indikator-inisiatif/{id_indikator_inisiatif}/program',
            [ProgramController::class, 'byInisiatif']
        )->name('program.by-inisiatif');
    });

    Route::middleware('akses.menu:ass_manager,lihat')->group(function () {
        Route::get(
            '/indikator-program/{id_indikator_program}/kegiatan',
            [KegiatanController::class, 'byProgram']
        )->name('kegiatan.by-program');
    });

    Route::middleware('akses.menu:staff,lihat')->group(function () {
        Route::get(
            'sub-kegiatan/indikator-kegiatan/{idIndikatorKegiatan}',
            [SubKegiatanController::class, 'byIndikatorKegiatan']
        )->name('sub-kegiatan.by-indikator-kegiatan');
    });

    /*
        |--------------------------------------------------------------------------
        | Resource per menu KPI Tracker
        |--------------------------------------------------------------------------
        | lihat  : index dan show
        | kelola : create, store, edit, update, destroy
        */
    $daftarResourcePerMenu = [
        'dir_utama' => [
            'sasaran-strategis' => SasaranStrategisController::class,
            'indikator-kpi'     => IndikatorKpiController::class,
        ],
        'dir_bagian' => [
            'sasaran-inisiatif'   => SasaranInisiatifController::class,
            'indikator-inisiatif' => IndikatorInisiatifController::class,
        ],
        'manager' => [
            'program'           => ProgramController::class,
            'indikator-program' => IndikatorProgramController::class,
            'pic-unit'          => PicUnitController::class,
        ],
        'ass_manager' => [
            'kegiatan'           => KegiatanController::class,
            'indikator-kegiatan' => IndikatorKegiatanController::class,
            'pic-staff'          => PicStaffController::class,
        ],
        'staff' => [
            'sub-kegiatan'            => SubKegiatanController::class,
            'indikator-sub-kegiatan'  => IndikatorSubKegiatanController::class,
        ],
    ];

    foreach ($daftarResourcePerMenu as $namaMenu => $daftarResource) {
        foreach ($daftarResource as $uriResource => $kelasController) {
            Route::middleware("akses.menu:{$namaMenu},lihat")->group(function () use ($uriResource, $kelasController) {
                Route::resource($uriResource, $kelasController)->only(['index', 'show']);
            });

            Route::middleware("akses.menu:{$namaMenu},kelola")->group(function () use ($uriResource, $kelasController) {
                Route::resource($uriResource, $kelasController)->except(['index', 'show']);
            });
        }
    }

    /*
        |--------------------------------------------------------------------------
        | Route lain (belum dibatasi per menu)
        |--------------------------------------------------------------------------
        */
    Route::patch('task-tracker/{task}/status', [TaskController::class, 'updateStatus'])->name('task.status');

    Route::resource('tahun', TahunController::class);
    Route::middleware('akses.menu:master_data,kelola')->group(function () {
        Route::resource('unit', UnitController::class);
        Route::resource('users', UserController::class);
    });
    Route::resource('monitoring', MonitoringController::class)
        ->only([
            'index',
            'show',
            'edit',
            'update',
        ]);
    Route::resource('file-pelaporan', FilePelaporanController::class);
    Route::resource('task-tracker', TaskController::class)
        ->parameters(['task-tracker' => 'task'])
        ->names('task')
        ->except(['show']);
});

Route::middleware('auth')->prefix('notifikasi')->name('notifikasi.')->group(function () {
    Route::get('/', [NotifikasiController::class, 'daftar'])->name('daftar');
    Route::post('/tandai-semua-dibaca', [NotifikasiController::class, 'tandaiSemuaDibaca'])->name('tandai-semua-dibaca');
    Route::get('/{idTask}/{jenisNotifikasi}/buka', [NotifikasiController::class, 'buka'])
        ->whereNumber('idTask')
        ->name('buka');
});

Route::middleware('auth')->prefix('export-laporan')->name('export-laporan.')->group(function () {
    Route::get('/', [ExportLaporanController::class, 'index'])->name('index');
    Route::get('/pdf', [ExportLaporanController::class, 'unduhPdf'])->name('pdf');
    Route::get('/excel', [ExportLaporanController::class, 'unduhExcel'])->name('excel');
});
