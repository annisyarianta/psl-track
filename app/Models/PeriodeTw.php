<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PeriodeTw extends Model
{
    protected $table = 'periode_tw';
    protected $primaryKey = 'id_periode_tw';
    public $timestamps = false;
    protected $fillable = ['id_kpi', 'triwulan'];

    public function kpi()
    {
        return $this->belongsTo(Kpi::class, 'id_kpi', 'id_kpi');
    }

    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'id_periode_tw', 'id_periode_tw');
    }
}
