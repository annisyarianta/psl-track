<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $table = 'unit';

    protected $primaryKey = 'id_unit';

    public $timestamps = false;

    protected $fillable = [
        'nama_unit',
    ];

    public function users()
    {
        return $this->hasMany(
            User::class,
            'id_unit',
            'id_unit'
        );
    }

    public function picUnits()
    {
        return $this->hasMany(
            PicUnit::class,
            'id_unit',
            'id_unit'
        );
    }
}