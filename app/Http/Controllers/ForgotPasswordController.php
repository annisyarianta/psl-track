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
            'email' => [
                'required',
                'email',
            ],
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {

            return back()->with(
                'success',
                'Link reset password telah dikirim ke email Anda.'
            );
        }

        return back()->withErrors([
            'email' => __($status),
        ]);
    }
}