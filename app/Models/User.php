<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';
    protected $primaryKey = 'id_user';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'nopeg',
        'email',
        'password',
        'role',
        'id_unit',
        'must_change_password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'must_change_password' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship dengan Unit
    |--------------------------------------------------------------------------
    */

    public function unit()
    {
        return $this->belongsTo(
            Unit::class,
            'id_unit',
            'id_unit'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship PIC Unit
    |--------------------------------------------------------------------------
    */

    public function picUnits()
    {
        return $this->hasMany(
            PicUnit::class,
            'assigned_by',
            'id_user'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship PIC Staff
    |--------------------------------------------------------------------------
    */

    public function picStaff()
    {
        return $this->hasMany(
            PicStaff::class,
            'id_user',
            'id_user'
        );
    }

    public function assignedPicStaff()
    {
        return $this->hasMany(
            PicStaff::class,
            'assigned_by',
            'id_user'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship Monitoring
    |--------------------------------------------------------------------------
    */

    public function monitoringUpdated()
    {
        return $this->hasMany(
            Monitoring::class,
            'last_updated_by',
            'id_user'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationship File Pelaporan
    |--------------------------------------------------------------------------
    */

    public function filesUploaded()
    {
        return $this->hasMany(
            FilePelaporan::class,
            'uploaded_by',
            'id_user'
        );
    }
}