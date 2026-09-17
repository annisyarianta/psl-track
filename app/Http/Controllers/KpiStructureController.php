<?php

namespace App\Http\Controllers;

use App\Models\Kpi;
use App\Models\SasaranProgram;
use App\Models\Program;
use App\Models\IndikatorProgram;
use App\Models\User;
use App\Models\PicIndikator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KpiStructureController extends Controller
{
    /**
     * Pilihan aspek indikator.
     */
    private array $aspekOptions = [
        'Kualitas',
        'Kuantitas',
    ];

    /**
     * Menampilkan halaman struktur KPI.
     */
    public function create($id_kpi)
    {
        $kpi = Kpi::findOrFail($id_kpi);
        $users = User::all();
        $aspekOptions = $this->aspekOptions;

        return view('kpi.struktur', compact(
            'kpi',
            'aspekOptions',
            'users'
        ));
    }

    /**
     * Menyimpan Sasaran → Program → Indikator.
     */
    public function store(Request $request, $id_kpi)
    {
        $kpi = Kpi::findOrFail($id_kpi);

        $validated = $request->validate([
            // =========================
            // SASARAN
            // =========================
            'nama_sasaran' => [
                'required',
                'string',
                'max:255',
            ],

            // =========================
            // PROGRAM
            // =========================
            'nama_program' => [
                'required',
                'string',
                'max:255',
            ],

            // =========================
            // INDIKATOR
            // =========================
            'nama_indikator' => [
                'required',
                'string',
                'max:255',
            ],

            'target' => [
                'nullable',
                'string',
                'max:100',
            ],

            'aspek' => [
                'nullable',
                'in:' . implode(',', $this->aspekOptions),
            ],

            'periode_pengukuran' => [
                'nullable',
                'string',
                'max:100',
            ],

            'upaya' => [
                'nullable',
                'string',
                'max:255',
            ],

            'id_user' => 'required|exists:users,id_user',

            'due_date' => [
                'nullable',
                'date',
            ],
        ]);

        try {
            DB::transaction(function () use ($validated, $kpi) {

                // ========================================
                // 1. SIMPAN SASARAN
                // ========================================

                $sasaran = SasaranProgram::create([
                    'id_kpi' => $kpi->id_kpi,
                    'nama_sasaran' => $validated['nama_sasaran'],
                ]);


                // ========================================
                // 2. SIMPAN PROGRAM
                // ========================================

                $program = Program::create([
                    'id_sasaran' => $sasaran->id_sasaran,
                    'nama_program' => $validated['nama_program'],
                ]);


                // ========================================
                // 3. SIMPAN INDIKATOR
                // ========================================

                $indikator = IndikatorProgram::create([
                    'id_program' => $program->id_program,
                    'nama_indikator' => $validated['nama_indikator'],
                    'target' => $validated['target'] ?? null,
                    'aspek' => $validated['aspek'] ?? null,
                    'periode_pengukuran' => $validated['periode_pengukuran'] ?? null,
                    'upaya' => $validated['upaya'] ?? null,
                    'due_date' => $validated['due_date'] ?? null,
                ]);

                PicIndikator::create([
                    'id_indikator' => $indikator->id_indikator,
                    'id_user' => $validated['id_user'],
                ]);
            });

            // Jika semuanya berhasil, kembali ke detail KPI.
            return redirect()
                ->route('kpi.show', $kpi->id_kpi)
                ->with(
                    'success',
                    'Sasaran, Program, dan Indikator berhasil ditambahkan.'
                );
        } catch (\Throwable $e) {

            // Jika terjadi error, kembali ke form.
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data gagal disimpan. Silakan coba kembali.'
                );
        }
    }
}
