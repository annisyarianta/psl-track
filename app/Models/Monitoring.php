<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $table = 'monitoring';
    protected $primaryKey = 'id_monitoring';
    public $timestamps = false;
    protected $fillable = [
        'id_periode_tw', 'id_indikator', 'capaian', 'keterangan',
        'identifikasi', 'last_updated_by', 'last_updated_at', 'status',
    ];

    public function periodeTw()
    {
        return $this->belongsTo(PeriodeTw::class, 'id_periode_tw', 'id_periode_tw');
    }

    public function indikatorProgram()
    {
        return $this->belongsTo(IndikatorProgram::class, 'id_indikator', 'id_indikator');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'last_updated_by', 'id_user');
    }

    public function filePelaporan()
    {
        return $this->hasMany(FilePelaporan::class, 'id_monitoring', 'id_monitoring');
    }
}
