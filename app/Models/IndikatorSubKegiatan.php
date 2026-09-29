<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorSubKegiatan extends Model
{
    protected $table = 'indikator_sub_kegiatan';

    protected $primaryKey = 'id_indikator_sub_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'id_sub_kegiatan',
        'nama_indikator_sub_kegiatan',
        'target_staff',
    ];

    public function subKegiatan()
    {
        return $this->belongsTo(
            SubKegiatan::class,
            'id_sub_kegiatan',
            'id_sub_kegiatan'
        );
    }

    public function monitoring()
    {
        return $this->hasMany(
            Monitoring::class,
            'id_indikator_sub_kegiatan',
            'id_indikator_sub_kegiatan'
        );
    }
}