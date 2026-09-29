<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorProgram extends Model
{
    protected $table = 'indikator_program';

    protected $primaryKey = 'id_indikator_program';

    public $timestamps = false;

    protected $fillable = [
        'id_program',
        'nama_indikator_program',
        'target_manager',
        'aspek',
        'periode_pengukuran',
    ];

    public function program()
    {
        return $this->belongsTo(
            Program::class,
            'id_program',
            'id_program'
        );
    }

    public function kegiatan()
    {
        return $this->hasMany(
            Kegiatan::class,
            'id_indikator_program',
            'id_indikator_program'
        );
    }

    public function picUnits()
    {
        return $this->hasMany(
            PicUnit::class,
            'id_indikator_program',
            'id_indikator_program'
        );
    }
}