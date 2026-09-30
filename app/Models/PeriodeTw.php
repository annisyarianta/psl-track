<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeTw extends Model
{
    protected $table = 'periode_tw';

    protected $primaryKey = 'id_periode_tw';

    public $timestamps = false;

    protected $fillable = [
        'id_tahun',
        'triwulan',
    ];

    public function tahun()
    {
        return $this->belongsTo(
            Tahun::class,
            'id_tahun',
            'id_tahun'
        );
    }

    public function monitorings()
    {
        return $this->hasMany(
            Monitoring::class,
            'id_periode_tw',
            'id_periode_tw'
        );
    }
}