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

    private array $aspekOptions = [
        'Kualitas',
        'Kuantitas',
    ];

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

    public function store(Request $request, $id_kpi)
    {
        $kpi = Kpi::findOrFail($id_kpi);

        $validated = $request->validate([
            'nama_sasaran' => [
                'required',
                'string',
                'max:255',
            ],

            'nama_program' => [
                'required',
                'string',
                'max:255',
            ],

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
                $sasaran = SasaranProgram::create([
                    'id_kpi' => $kpi->id_kpi,
                    'nama_sasaran' => $validated['nama_sasaran'],
                ]);

                $program = Program::create([
                    'id_sasaran' => $sasaran->id_sasaran,
                    'nama_program' => $validated['nama_program'],
                ]);

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

            return redirect()
                ->route('kpi.show', $kpi->id_kpi)
                ->with(
                    'success',
                    'Sasaran, Program, dan Indikator berhasil ditambahkan.'
                );
        } catch (\Throwable $e) {

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Data gagal disimpan. Silakan coba kembali.'
                );
        }
    }
}
