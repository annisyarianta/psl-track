<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorKegiatan extends Model
{
    protected $table = 'indikator_kegiatan';

    protected $primaryKey = 'id_indikator_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'id_kegiatan',
        'nama_indikator_kegiatan',
        'target_asmen',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(
            Kegiatan::class,
            'id_kegiatan',
            'id_kegiatan'
        );
    }

    public function subKegiatan()
    {
        return $this->hasMany(
            SubKegiatan::class,
            'id_indikator_kegiatan',
            'id_indikator_kegiatan'
        );
    }

    public function picStaff()
    {
        return $this->hasMany(
            PicStaff::class,
            'id_indikator_kegiatan',
            'id_indikator_kegiatan'
        );
    }
}