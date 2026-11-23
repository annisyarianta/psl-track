<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use App\Models\Tahun;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        ResetPassword::toMailUsing(function ($pengguna, $tokenReset) {
            $linkResetPassword = url(route('password.reset', [
                'token' => $tokenReset,
                'email' => $pengguna->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Atur Ulang Password PSL Track')
                ->view('emails.reset-password', [
                    'namaPengguna' => $pengguna->nama,
                    'linkResetPassword' => $linkResetPassword,
                    'masaBerlakuMenit' => config('auth.passwords.users.expire'),
                ]);
        });

        View::composer([
            'sasaran_strategis.index',
            'sasaran_inisiatif.index',
            'program.index',
            'kegiatan.index',
            'sub_kegiatan.index',
        ], function ($view) {
            $view->with('daftarTahun', Tahun::where('tahun', '<=', now()->year)
                ->orderBy('tahun', 'desc')
                ->get());
        });
    }
}
