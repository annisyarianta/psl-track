<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorProgram extends Model
{
    protected $table = 'indikator_program';
    protected $primaryKey = 'id_indikator';
    public $timestamps = false;
    protected $fillable = [
        'id_program',
        'nama_indikator',
        'target',
        'aspek',
        'periode_pengukuran',
        'upaya',
        'due_date',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program', 'id_program');
    }

    public function picIndikator()
    {
        return $this->hasMany(PicIndikator::class, 'id_indikator', 'id_indikator');
    }

    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'id_indikator', 'id_indikator');
    }
}
