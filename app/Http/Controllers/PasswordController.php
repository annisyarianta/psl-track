<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    /**
     * Form ganti password
     */
    public function edit()
    {
        return view('password.change');
    }

    /**
     * Proses ganti password
     */
    public function update(Request $request)
    {
        $request->validate([
            'current_password' => [
                'required',
            ],

            'password' => [
                'required',
                'min:8',
                'confirmed',
            ],
        ]);

        $user = $request->user();

        if (!Hash::check(
            $request->current_password,
            $user->password
        )) {

            return back()->withErrors([
                'current_password' =>
                    'Password saat ini salah.',
            ]);
        }

        $user->update([
            'password' => Hash::make(
                $request->password
            ),

            'must_change_password' => false,
        ]);

        return redirect()
            ->route('dashboard')
            ->with(
                'success',
                'Password berhasil diubah.'
            );
    }
}