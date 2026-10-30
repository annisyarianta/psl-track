<!doctype html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-kemenkes.png') }}">
    <title>Atur Ulang Password</title>
</head>

<body style="margin:0; padding:0; background-color:#f1f5f9; font-family:'Segoe UI', Arial, sans-serif;">

    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f1f5f9;">
        <tr>
            <td align="center" style="padding:32px 16px;">

                <!-- Kartu utama -->
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="max-width:600px; width:100%; background-color:#ffffff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden;">

                    <!-- Header: logo dan garis tosca -->
                    <tr>
                        <td align="center" style="padding:28px 24px 20px; border-bottom:4px solid #00beb4;">
                            <img src="{{ asset('assets/images/logo-harkit.png') }}" alt="PSL Track" height="56"
                                style="height:56px; width:auto; display:block;">
                        </td>
                    </tr>

                    <!-- Sapaan dan penjelasan -->
                    <tr>
                        <td style="padding:36px 40px 12px;">
                            <h1 style="margin:0 0 16px; font-size:22px; color:#1e293b;">
                                Halo, {{ $namaPengguna }}!
                            </h1>
                            <p style="margin:0 0 12px; font-size:15px; line-height:1.7; color:#475569;">
                                Kami menerima permintaan untuk mengatur ulang password akun
                                <strong>PSL Track</strong> Anda. Klik tombol di bawah ini untuk membuat password baru.
                            </p>
                        </td>
                    </tr>

                    <!-- Tombol -->
                    <tr>
                        <td align="center" style="padding:16px 40px 24px;">
                            <a href="{{ $linkResetPassword }}"
                                style="display:inline-block; background-color:#5156be; color:#ffffff; font-size:15px; font-weight:bold; text-decoration:none; padding:14px 32px; border-radius:8px;">
                                Atur Ulang Password
                            </a>
                        </td>
                    </tr>

                    <!-- Info masa berlaku -->
                    <tr>
                        <td style="padding:0 40px 28px;">
                            <table role="presentation" width="100%" cellpadding="0" cellspacing="0"
                                style="background-color:#f0fdfa; border-left:4px solid #00beb4; border-radius:6px;">
                                <tr>
                                    <td style="padding:12px 16px; font-size:14px; line-height:1.6; color:#475569;">
                                        Link ini berlaku selama <strong>{{ $masaBerlakuMenit }} menit</strong>.
                                        Jika Anda tidak merasa meminta pengaturan ulang password, abaikan email ini.
                                        Password Anda tidak akan berubah.
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Link cadangan -->
                    <tr>
                        <td style="padding:20px 40px 28px; border-top:1px solid #e2e8f0;">
                            <p style="margin:0 0 8px; font-size:12px; line-height:1.6; color:#94a3b8;">
                                Jika tombol di atas tidak berfungsi, salin dan tempel link berikut ke browser Anda:
                            </p>
                            <p style="margin:0; font-size:12px; line-height:1.6; word-break:break-all;">
                                <a href="{{ $linkResetPassword }}" style="color:#5156be;">{{ $linkResetPassword }}</a>
                            </p>
                        </td>
                    </tr>

                </table>

                <!-- Footer -->
                <table role="presentation" width="600" cellpadding="0" cellspacing="0"
                    style="max-width:600px; width:100%;">
                    <tr>
                        <td align="center" style="padding:20px 16px; font-size:12px; line-height:1.6; color:#94a3b8;">
                            Email ini dikirim otomatis oleh sistem {{ config('app.name') }}. Mohon tidak membalas email
                            ini.<br>
                            &copy; {{ date('Y') }} {{ config('app.name') }}
                        </td>
                    </tr>
                </table>

            </td>
        </tr>
    </table>

</body>

</html>
