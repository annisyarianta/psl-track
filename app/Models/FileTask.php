<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FileTask extends Model
{
    protected $table = 'file_task';
    protected $primaryKey = 'id_file_task';
    public $timestamps = false;

    protected $fillable = [
        'id_task',
        'nama_file',
        'path_file',
        'uploaded_by',
        'uploaded_at',
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class, 'id_task', 'id_task');
    }

    public function pengunggah()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id_user');
    }
}