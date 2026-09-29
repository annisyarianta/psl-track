<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';

    protected $primaryKey = 'id_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'id_indikator_program',
        'nama_kegiatan',
    ];

    public function indikatorProgram()
    {
        return $this->belongsTo(
            IndikatorProgram::class,
            'id_indikator_program',
            'id_indikator_program'
        );
    }

    public function indikatorKegiatan()
    {
        return $this->hasMany(
            IndikatorKegiatan::class,
            'id_kegiatan',
            'id_kegiatan'
        );
    }
}