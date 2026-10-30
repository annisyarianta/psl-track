<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Form lupa password
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    /**
     * Kirim link reset password
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $statusPengiriman = Password::sendResetLink($request->only('email'));

        if ($statusPengiriman === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password telah dikirim ke email Anda.');
        }

        $daftarPesanGagal = [
            Password::INVALID_USER => 'Email tidak terdaftar.',
            Password::RESET_THROTTLED => 'Terlalu sering meminta link. Silakan coba lagi beberapa saat lagi.',
        ];

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => $daftarPesanGagal[$statusPengiriman] ?? 'Gagal mengirim link reset password.',
            ]);
    }
}
