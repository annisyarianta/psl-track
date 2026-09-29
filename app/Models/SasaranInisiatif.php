<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SasaranInisiatif extends Model
{
    protected $table = 'sasaran_inisiatif';

    protected $primaryKey = 'id_sasaran_inisiatif';

    public $timestamps = false;

    protected $fillable = [
        'id_indikator_kpi',
        'nama_sasaran_inisiatif',
    ];

    public function indikatorKpi()
    {
        return $this->belongsTo(
            IndikatorKpi::class,
            'id_indikator_kpi',
            'id_indikator_kpi'
        );
    }

    public function indikatorInisiatif()
    {
        return $this->hasMany(
            IndikatorInisiatif::class,
            'id_sasaran_inisiatif',
            'id_sasaran_inisiatif'
        );
    }
}