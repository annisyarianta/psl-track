<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilePelaporan extends Model
{
    protected $table = 'file_pelaporan';
    protected $primaryKey = 'id_file';
    public $timestamps = false;
    protected $fillable = ['id_monitoring', 'nama_file', 'path_file', 'uploaded_by', 'uploaded_at'];

    public function monitoring()
    {
        return $this->belongsTo(Monitoring::class, 'id_monitoring', 'id_monitoring');
    }

    public function uploadedBy()
    {
        return $this->belongsTo(User::class, 'uploaded_by', 'id_user');
    }
}
