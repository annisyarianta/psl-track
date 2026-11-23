<?php

namespace App\Models;

use App\Models\Concerns\FilterTahun;
use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    use FilterTahun;
    protected $relasiTahun = 'indikatorKegiatan.kegiatan.indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun';
    protected $table = 'sub_kegiatan';
    protected $primaryKey = 'id_sub_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'id_indikator_kegiatan',
        'judul_sub_kegiatan',
        'isi_sub_kegiatan',
    ];

    public function indikatorKegiatan()
    {
        return $this->belongsTo(
            IndikatorKegiatan::class,
            'id_indikator_kegiatan',
            'id_indikator_kegiatan'
        );
    }

    public function indikatorSubKegiatan()
    {
        return $this->hasMany(
            IndikatorSubKegiatan::class,
            'id_sub_kegiatan',
            'id_sub_kegiatan'
        );
    }
}
