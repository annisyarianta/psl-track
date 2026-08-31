<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ResetPasswordController;



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

    Route::resource('users', UserController::class)
        ->except([
            'show'
        ]);

});


Route::get('/dashboard', function () {
    return view('dashboard');
})
->middleware([
    'auth',
    'force.password.change'
])
->name('dashboard');