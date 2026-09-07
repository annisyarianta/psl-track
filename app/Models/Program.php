<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $table = 'program';
    protected $primaryKey = 'id_program';
    public $timestamps = false;
    protected $fillable = ['id_sasaran', 'nama_program'];

    public function sasaranProgram()
    {
        return $this->belongsTo(SasaranProgram::class, 'id_sasaran', 'id_sasaran');
    }

    public function indikatorProgram()
    {
        return $this->hasMany(IndikatorProgram::class, 'id_program', 'id_program');
    }
}
