<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilePasswordController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('password.change');
    }

    public function updatePassword(Request $request)
    {
        $validatedInput = $request->validate([
            'password_saat_ini'        => ['required', 'current_password'],
            'password_baru'            => ['required', 'string', 'min:8', 'different:password_saat_ini'],
            'konfirmasi_password_baru' => ['required', 'same:password_baru'],
        ], [
            'password_saat_ini.current_password' => 'Password saat ini tidak sesuai.',
            'password_baru.min'                  => 'Password baru minimal 8 karakter.',
            'password_baru.different'            => 'Password baru tidak boleh sama dengan password saat ini.',
            'konfirmasi_password_baru.same'      => 'Konfirmasi password tidak cocok.',
        ]);

        $loggedInUser = Auth::user();
        $loggedInUser->password = Hash::make($validatedInput['password_baru']);
        $loggedInUser->must_change_password = 0;
        $loggedInUser->save();

        return redirect()
            ->route('profile.password.edit')
            ->with('success', 'Password berhasil diubah.');
    }
}