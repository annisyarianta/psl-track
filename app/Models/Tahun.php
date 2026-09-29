<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tahun extends Model
{
    protected $table = 'tahun';

    protected $primaryKey = 'id_tahun';

    public $timestamps = false;

    protected $fillable = [
        'tahun',
    ];

    public function sasaranStrategis()
    {
        return $this->hasMany(
            SasaranStrategis::class,
            'id_tahun',
            'id_tahun'
        );
    }

    public function periodeTw()
    {
        return $this->hasMany(
            PeriodeTw::class,
            'id_tahun',
            'id_tahun'
        );
    }
}