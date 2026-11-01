<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CekAksesMenu
{
    public function handle(Request $request, Closure $next, string $namaMenu, string $jenisAkses)
    {
        $pengguna = $request->user();

        $diizinkan = $jenisAkses === 'kelola'
            ? $pengguna->bisaKelolaMenu($namaMenu)
            : $pengguna->bisaLihatMenu($namaMenu);

        abort_unless($diizinkan, 403);

        return $next($request);
    }
}