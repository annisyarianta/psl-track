<?php

namespace App\Http\Controllers;

use App\Models\Tahun;

class TahunController extends Controller
{
    public function index()
    {
        $tahun = Tahun::with('periode')
            ->orderByDesc('tahun')
            ->get();

        return view('tahun.index', compact('tahun'));
    }
}
