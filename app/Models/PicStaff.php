<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PicStaff extends Model
{
    protected $table = 'pic_staff';

    protected $primaryKey = 'id_pic_staff';

    public $timestamps = false;

    protected $fillable = [
        'id_indikator_kegiatan',
        'id_user',
        'assigned_by',
        'assigned_at',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
    ];

    public function indikatorKegiatan()
    {
        return $this->belongsTo(
            IndikatorKegiatan::class,
            'id_indikator_kegiatan',
            'id_indikator_kegiatan'
        );
    }

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'id_user',
            'id_user'
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