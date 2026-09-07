<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SasaranProgram extends Model
{
    protected $table = 'sasaran_program';
    protected $primaryKey = 'id_sasaran';
    public $timestamps = false;
    protected $fillable = ['id_kpi', 'nama_sasaran'];

    public function kpi()
    {
        return $this->belongsTo(Kpi::class, 'id_kpi', 'id_kpi');
    }

    public function program()
    {
        return $this->hasMany(Program::class, 'id_sasaran', 'id_sasaran');
    }
}
