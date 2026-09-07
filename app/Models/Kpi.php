<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kpi extends Model
{
    protected $table = 'kpi';
    protected $primaryKey = 'id_kpi';
    public $timestamps = false;
    protected $fillable = ['tahun', 'judul_kpi'];

    public function sasaranProgram()
    {
        return $this->hasMany(SasaranProgram::class, 'id_kpi', 'id_kpi');
    }

    public function periodeTw()
    {
        return $this->hasMany(PeriodeTw::class, 'id_kpi', 'id_kpi');
    }
}
