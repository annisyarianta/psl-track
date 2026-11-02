<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaporanKpiExport implements FromView, WithColumnWidths, WithStyles
{
    // baris 1 = judul, baris 2 = unit, baris 3 = kosong, baris 4 = judul kolom
    private const NOMOR_BARIS_JUDUL_KOLOM = 4;

    public function __construct(
        private array $daftarJudul,
        private array $daftarBaris,
        private string $judulLaporan,
        private string $keteranganUnit,
    ) {}

    public function view(): View
    {
        return view('export_laporan.tabel', [
            'daftarJudul'    => $this->daftarJudul,
            'daftarBaris'    => $this->daftarBaris,
            'judulLaporan'   => $this->judulLaporan,
            'keteranganUnit' => $this->keteranganUnit,
        ]);
    }

    public function columnWidths(): array
    {
        return array_fill_keys(range('A', 'J'), 32);
    }

    public function styles(Worksheet $lembarKerja)
    {
        $nomorBarisJudulKolom = self::NOMOR_BARIS_JUDUL_KOLOM;
        $nomorBarisTerakhir = $lembarKerja->getHighestRow();

        $rentangJudulLaporan = 'A1:J2';
        $rentangJudulKolom = "A{$nomorBarisJudulKolom}:J{$nomorBarisJudulKolom}";
        $rentangTabel = "A{$nomorBarisJudulKolom}:J{$nomorBarisTerakhir}";

        // judul laporan dan keterangan unit
        $lembarKerja->getStyle($rentangJudulLaporan)->getFont()->setBold(true);
        $lembarKerja->getStyle('A1')->getFont()->setSize(14);
        $lembarKerja->getStyle($rentangJudulLaporan)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // tabel data
        $lembarKerja->getStyle($rentangTabel)->getAlignment()
            ->setWrapText(true)
            ->setVertical(Alignment::VERTICAL_CENTER);
        $lembarKerja->getStyle($rentangTabel)->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        // judul kolom
        $lembarKerja->getStyle($rentangJudulKolom)->getFont()->setBold(true);
        $lembarKerja->getStyle($rentangJudulKolom)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // judul kolom tetap terlihat saat scroll dan berulang di tiap halaman cetak
        $lembarKerja->freezePane('A' . ($nomorBarisJudulKolom + 1));
        $lembarKerja->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd($nomorBarisJudulKolom, $nomorBarisJudulKolom);

        $lembarKerja->getPageSetup()->setOrientation('landscape');
        $lembarKerja->getPageSetup()->setPaperSize(8); // A3
        $lembarKerja->getPageSetup()->setFitToWidth(1);
        $lembarKerja->getPageSetup()->setFitToHeight(0);
    }
}