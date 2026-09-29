<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubKegiatan extends Model
{
    protected $table = 'sub_kegiatan';

    protected $primaryKey = 'id_sub_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'id_indikator_kegiatan',
        'nama_sub_kegiatan',
    ];

    public function indikatorKegiatan()
    {
        return $this->belongsTo(
            IndikatorKegiatan::class,
            'id_indikator_kegiatan',
            'id_indikator_kegiatan'
        );
    }

    public function indikatorSubKegiatan()
    {
        return $this->hasMany(
            IndikatorSubKegiatan::class,
            'id_sub_kegiatan',
            'id_sub_kegiatan'
        );
    }
}