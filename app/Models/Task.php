<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $table = 'task';
    protected $primaryKey = 'id_task';
    public $timestamps = false;

    public const DAFTAR_STATUS = [
        'todo'       => 'To Do',
        'inprogress' => 'In Progress',
        'done'       => 'Done',
    ];

    public const WARNA_BADGE_STATUS = [
        'todo'       => 'badge-soft-dark small px-2 py-1',
        'inprogress' => 'badge-soft-info small px-2 py-1',
        'done'       => 'badge-soft-success small px-2 py-1',
    ];

    protected $fillable = [
        'id_program',
        'judul_tugas',
        'deskripsi_tugas',
        'tenggat_waktu',
        'status',
        'assigned_by',
        'assigned_at',
    ];

    protected $casts = [
        'tenggat_waktu' => 'date',
        'assigned_at'   => 'datetime',
    ];

    public function program()
    {
        return $this->belongsTo(Program::class, 'id_program', 'id_program');
    }

    public function pemberiTugas()
    {
        return $this->belongsTo(User::class, 'assigned_by', 'id_user');
    }

    public function penerimaTugas()
    {
        return $this->belongsToMany(User::class, 'pic_task', 'id_task', 'id_user');
    }

    public function files()
    {
        return $this->hasMany(FileTask::class, 'id_task', 'id_task');
    }

    // Task yang diberikan oleh user ini ATAU ditugaskan kepada user ini
    public function scopeMilikUser($query, int $idUser)
    {
        return $query->where(function ($subQuery) use ($idUser) {
            $subQuery->where('assigned_by', $idUser)
                ->orWhereHas('penerimaTugas', function ($queryPenerima) use ($idUser) {
                    $queryPenerima->where('users.id_user', $idUser);
                });
        });
    }

    public function apakahPemberiTugas(int $idUser): bool
    {
        return (int) $this->assigned_by === $idUser;
    }

    public function apakahPenerimaTugas(int $idUser): bool
    {
        return $this->penerimaTugas->contains('id_user', $idUser);
    }
}
