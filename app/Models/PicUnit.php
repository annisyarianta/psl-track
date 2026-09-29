<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PicUnit extends Model
{
    protected $table = 'pic_unit';

    protected $primaryKey = 'id_pic_unit';

    public $timestamps = false;

    protected $fillable = [
        'id_indikator_program',
        'id_unit',
        'assigned_by',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function indikatorProgram()
    {
        return $this->belongsTo(
            IndikatorProgram::class,
            'id_indikator_program',
            'id_indikator_program'
        );
    }

    public function unit()
    {
        return $this->belongsTo(
            Unit::class,
            'id_unit',
            'id_unit'
        );
    }

    public function assignedBy()
    {
        return $this->belongsTo(
            User::class,
            'assigned_by',
            'id_user'
        );
    }
}