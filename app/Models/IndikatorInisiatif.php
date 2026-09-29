<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorInisiatif extends Model
{
    protected $table = 'indikator_inisiatif';

    protected $primaryKey = 'id_indikator_inisiatif';

    public $timestamps = false;

    protected $fillable = [
        'id_sasaran_inisiatif',
        'target_dirbag',
        'nama_indikator_inisiatif',
    ];

    public function sasaranInisiatif()
    {
        return $this->belongsTo(
            SasaranInisiatif::class,
            'id_sasaran_inisiatif',
            'id_sasaran_inisiatif'
        );
    }

    public function programs()
    {
        return $this->hasMany(
            Program::class,
            'id_indikator_inisiatif',
            'id_indikator_inisiatif'
        );
    }
}