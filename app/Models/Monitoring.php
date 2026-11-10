<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    protected $table = 'monitoring';

    protected $primaryKey = 'id_monitoring';

    public $timestamps = false;

    protected $fillable = [
        'id_periode',
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

    public function periode()
    {
        return $this->belongsTo(
            Periode::class,
            'id_periode',
            'id_periode'
        );
    }

    public function sudahTerisi(): bool
    {
        return ! in_array($this->status, [null, 'notstarted'], true)
            || filled($this->upaya)
            || filled($this->capaian)
            || filled($this->keterangan)
            || filled($this->identifikasi)
            || $this->files()->exists();
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
