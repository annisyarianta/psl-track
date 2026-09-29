<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorKpi extends Model
{
    protected $table = 'indikator_kpi';

    protected $primaryKey = 'id_indikator_kpi';

    public $timestamps = false;

    protected $fillable = [
        'id_sasaran_strategis',
        'target_dirbag',
        'nama_indikator_kpi',
    ];

    public function sasaranStrategis()
    {
        return $this->belongsTo(
            SasaranStrategis::class,
            'id_sasaran_strategis',
            'id_sasaran_strategis'
        );
    }

    public function sasaranInisiatif()
    {
        return $this->hasMany(
            SasaranInisiatif::class,
            'id_indikator_kpi',
            'id_indikator_kpi'
        );
    }
}