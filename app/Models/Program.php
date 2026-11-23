<?php

namespace App\Models;

use App\Models\Concerns\FilterTahun;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use FilterTahun;
    protected $relasiTahun = 'indikatorInisiatif.sasaranInisiatif.indikatorKpi.sasaranStrategis.tahun';
    protected $table = 'program';
    protected $primaryKey = 'id_program';

    public $timestamps = false;

    protected $fillable = [
        'id_indikator_inisiatif',
        'nama_program',
    ];

    public function indikatorInisiatif()
    {
        return $this->belongsTo(
            IndikatorInisiatif::class,
            'id_indikator_inisiatif',
            'id_indikator_inisiatif'
        );
    }

    public function indikatorProgram()
    {
        return $this->hasMany(
            IndikatorProgram::class,
            'id_program',
            'id_program'
        );
    }
}