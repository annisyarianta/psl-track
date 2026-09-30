<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PasswordController;
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
use App\Http\Controllers\PeriodeTwController;
use App\Http\Controllers\PicUnitController;
use App\Http\Controllers\PicStaffController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\FilePelaporanController;


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
    ])->name('login');

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
});


Route::middleware([
    'auth',
    'force.password.change'
])->group(function () {
    Route::get(
        '/indikator-kpi/{id_indikator_kpi}/struktur',
        [IndikatorKpiController::class, 'struktur']
    )->name('indikator-kpi.struktur');
    Route::get(
        '/sasaran-inisiatif/{id_sasaran_inisiatif}/indikator',
        [SasaranInisiatifController::class, 'indikator']
    )->name('sasaran-inisiatif.indikator');
    Route::get(
        '/indikator-inisiatif/{id_indikator_inisiatif}/program',
        [IndikatorInisiatifController::class, 'program']
    )->name('indikator-inisiatif.program');
    Route::get(
        '/program/{id_program}/indikator',
        [ProgramController::class, 'indikator']
    )->name('program.indikator');
    Route::get(
        '/indikator-program/{id_indikator_program}/kegiatan',
        [IndikatorProgramController::class, 'kegiatan']
    )->name('indikator-program.kegiatan');
    Route::get(
        '/kegiatan/{id_kegiatan}/indikator',
        [KegiatanController::class, 'indikator']
    )->name('kegiatan.indikator');
    Route::get(
        '/indikator-kegiatan/{id_indikator_kegiatan}/sub-kegiatan',
        [IndikatorKegiatanController::class, 'subKegiatan']
    )->name('indikator-kegiatan.sub-kegiatan');
    Route::get(
        '/indikator-sub-kegiatan/{id_indikator_sub_kegiatan}/monitoring',
        [IndikatorSubKegiatanController::class, 'monitoring']
    )->name('indikator-sub-kegiatan.monitoring');


    Route::resource('tahun', TahunController::class);
    Route::resource('unit', UnitController::class);
    Route::resource('users', UserController::class);
    Route::resource('sasaran-strategis', SasaranStrategisController::class);
    Route::resource('indikator-kpi', IndikatorKpiController::class);
    Route::resource('sasaran-inisiatif', SasaranInisiatifController::class);
    Route::resource('indikator-inisiatif', IndikatorInisiatifController::class);
    Route::resource('program', ProgramController::class);
    Route::resource('indikator-program', IndikatorProgramController::class);
    Route::resource('kegiatan', KegiatanController::class);
    Route::resource('indikator-kegiatan', IndikatorKegiatanController::class);
    Route::resource('sub-kegiatan', SubKegiatanController::class);
    Route::resource('indikator-sub-kegiatan', IndikatorSubKegiatanController::class);
    Route::resource('periode-tw', PeriodeTwController::class);
    Route::resource('pic-unit', PicUnitController::class);
    Route::resource('pic-staff', PicStaffController::class);
    Route::resource('monitoring', MonitoringController::class)
        ->only([
            'index',
            'show',
            'edit',
            'update',
        ]);
    Route::resource('file-pelaporan', FilePelaporanController::class);
});
