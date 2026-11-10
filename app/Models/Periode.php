<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periode extends Model
{
    protected $table = 'periode';
    protected $primaryKey = 'id_periode';
    public $timestamps = false;

    protected $fillable = ['id_tahun', 'jenis_periode', 'urutan_periode'];

    public const JUMLAH_PERIODE_PER_JENIS = [
        'bulanan'  => 12,
        'triwulan' => 4,
        'semester' => 2,
        'tahunan'  => 1,
    ];

    private const NAMA_BULAN = [
        1 => 'Januari',
        2 => 'Februari',
        3 => 'Maret',
        4 => 'April',
        5 => 'Mei',
        6 => 'Juni',
        7 => 'Juli',
        8 => 'Agustus',
        9 => 'September',
        10 => 'Oktober',
        11 => 'November',
        12 => 'Desember',
    ];

    public function tahun()
    {
        return $this->belongsTo(Tahun::class, 'id_tahun', 'id_tahun');
    }

    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'id_periode', 'id_periode');
    }

    public function getNamaPeriodeAttribute(): string
    {
        return match ($this->jenis_periode) {
            'bulanan'  => self::NAMA_BULAN[$this->urutan_periode],
            'triwulan' => 'Triwulan ' . $this->urutan_periode,
            'semester' => 'Semester ' . $this->urutan_periode,
            'tahunan'  => 'Tahunan',
        };
    }

    public static function buatUntukTahun(int $idTahun): void
    {
        foreach (self::JUMLAH_PERIODE_PER_JENIS as $jenisPeriode => $jumlahPeriode) {
            for ($urutanPeriode = 1; $urutanPeriode <= $jumlahPeriode; $urutanPeriode++) {
                self::firstOrCreate([
                    'id_tahun'       => $idTahun,
                    'jenis_periode'  => $jenisPeriode,
                    'urutan_periode' => $urutanPeriode,
                ]);
            }
        }
    }
}
