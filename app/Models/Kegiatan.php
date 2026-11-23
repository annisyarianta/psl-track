<?php

namespace App\Models;

use App\Models\Concerns\FilterTahun;
use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    use FilterTahun;
    protected $relasiTahun = 'indikatorProgram.program.indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun';
    protected $table = 'kegiatan';

    protected $primaryKey = 'id_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'id_indikator_program',
        'nama_kegiatan',
    ];

    public function indikatorProgram()
    {
        return $this->belongsTo(
            IndikatorProgram::class,
            'id_indikator_program',
            'id_indikator_program'
        );
    }

    public function indikatorKegiatan()
    {
        return $this->hasMany(
            IndikatorKegiatan::class,
            'id_kegiatan',
            'id_kegiatan'
        );
    }
}