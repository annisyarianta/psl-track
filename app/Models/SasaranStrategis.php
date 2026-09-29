<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SasaranStrategis extends Model
{
    protected $table = 'sasaran_strategis';

    protected $primaryKey = 'id_sasaran_strategis';

    public $timestamps = false;

    protected $fillable = [
        'id_tahun',
        'nama_sasaran_strategis',
    ];

    public function tahun()
    {
        return $this->belongsTo(
            Tahun::class,
            'id_tahun',
            'id_tahun'
        );
    }

    public function indikatorKpi()
    {
        return $this->hasMany(
            IndikatorKpi::class,
            'id_sasaran_strategis',
            'id_sasaran_strategis'
        );
    }
}