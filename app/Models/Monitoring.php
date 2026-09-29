<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $table = 'monitoring';

    protected $primaryKey = 'id_monitoring';

    public $timestamps = false;

    protected $fillable = [
        'id_periode_tw',
        'id_indikator_sub_kegiatan',
        'upaya',
        'capaian',
        'keterangan',
        'identifikasi',
        'last_updated_by',
        'last_updated_at',
        'status',
    ];

    protected $casts = [
        'last_updated_at' => 'datetime',
    ];

    public function periodeTw()
    {
        return $this->belongsTo(
            PeriodeTw::class,
            'id_periode_tw',
            'id_periode_tw'
        );
    }

    public function indikatorSubKegiatan()
    {
        return $this->belongsTo(
            IndikatorSubKegiatan::class,
            'id_indikator_sub_kegiatan',
            'id_indikator_sub_kegiatan'
        );
    }

    public function lastUpdatedBy()
    {
        return $this->belongsTo(
            User::class,
            'last_updated_by',
            'id_user'
        );
    }

    public function files()
    {
        return $this->hasMany(
            FilePelaporan::class,
            'id_monitoring',
            'id_monitoring'
        );
    }
}