<?php

namespace App\Http\Controllers;

use App\Exports\LaporanKpiExport;
use App\Models\Tahun;
use App\Services\LaporanKpiService;
use App\Models\Unit;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Str;

class ExportLaporanController extends Controller
{
    public function index()
    {
        $daftarTahun = Tahun::orderBy('tahun')->get();
        $daftarUnit = Unit::orderBy('nama_unit')->get();

        return view('export_laporan.index', compact('daftarTahun', 'daftarUnit'));
    }

    public function unduhPdf(Request $request, LaporanKpiService $layananLaporanKpi)
    {
        $tahun = $this->ambilTahun($request);
        $unit = $this->ambilUnit($request);

        $daftarBaris = $layananLaporanKpi->susunBaris($tahun->id_tahun, $unit?->id_unit);
        if (empty($daftarBaris)) {
            return back()->with('error', 'Tidak ada data untuk filter yang dipilih.');
        }

        $pdf = Pdf::loadView('export_laporan.pdf', [
            'tahun'       => $tahun,
            'unit'        => $unit,
            'daftarJudul' => $layananLaporanKpi->daftarJudulKolom(),
            'daftarBaris' => $daftarBaris,
        ])->setPaper('a3', 'landscape');

        return $pdf->download($this->buatNamaFile($tahun, $unit, 'pdf'));
    }

    public function unduhExcel(Request $request, LaporanKpiService $layananLaporanKpi)
    {
        $tahun = $this->ambilTahun($request);
        $unit = $this->ambilUnit($request);

        $daftarBaris = $layananLaporanKpi->susunBaris($tahun->id_tahun, $unit?->id_unit);
        if (empty($daftarBaris)) {
            return back()->with('error', 'Tidak ada data untuk filter yang dipilih.');
        }

        return Excel::download(
            new LaporanKpiExport(
                $layananLaporanKpi->daftarJudulKolom(),
                $daftarBaris,
                "Laporan KPI Tahun {$tahun->tahun}",
                'Unit: ' . ($unit ? $unit->nama_unit : 'Semua unit'),
            ),
            $this->buatNamaFile($tahun, $unit, 'xlsx')
        );
    }

    private function ambilTahun(Request $request): Tahun
    {
        $request->validate(['id_tahun' => 'required|exists:tahun,id_tahun']);
        return Tahun::findOrFail($request->id_tahun);
    }

    private function ambilUnit(Request $request): ?Unit
    {
        $request->validate(['id_unit' => 'nullable|exists:unit,id_unit']);
        return $request->filled('id_unit') ? Unit::findOrFail($request->id_unit) : null;
    }

    private function buatNamaFile(Tahun $tahun, ?Unit $unit, string $ekstensi): string
    {
        $bagianUnit = $unit ? '-' . Str::slug($unit->nama_unit) : '';
        return "laporan-kpi-{$tahun->tahun}{$bagianUnit}.{$ekstensi}";
    }
}
