<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PicIndikator extends Model
{
    protected $table = 'pic_indikator';
    protected $primaryKey = 'id_pic_indikator';
    public $timestamps = false;
    protected $fillable = ['id_indikator', 'id_user'];

    public function indikatorProgram()
    {
        return $this->belongsTo(IndikatorProgram::class, 'id_indikator', 'id_indikator');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }
}
