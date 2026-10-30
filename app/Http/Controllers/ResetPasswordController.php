<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    /**
     * Form reset password
     */
    public function create(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Proses reset password
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $statusReset = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($pengguna, $passwordBaru) {
                $pengguna->forceFill([
                    'password' => Hash::make($passwordBaru),
                    'must_change_password' => false,
                ])->save();
            }
        );

        if ($statusReset === Password::PASSWORD_RESET) {
            return redirect()
                ->route('login')
                ->with('success', 'Password berhasil direset. Silakan login.');
        }

        $daftarPesanGagal = [
            Password::INVALID_TOKEN => 'Link reset tidak valid atau sudah kedaluwarsa. Silakan minta link baru.',
            Password::INVALID_USER => 'Email tidak ditemukan.',
        ];

        return back()
            ->withInput($request->only('email'))
            ->withErrors([
                'email' => $daftarPesanGagal[$statusReset] ?? 'Gagal mengatur ulang password.',
            ]);
    }
}