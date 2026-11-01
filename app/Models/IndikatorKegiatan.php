<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorKegiatan extends Model
{
    protected $table = 'indikator_kegiatan';

    protected $primaryKey = 'id_indikator_kegiatan';

    public $timestamps = false;

    protected $fillable = [
        'id_kegiatan',
        'nama_indikator_kegiatan',
        'target_asmen',
    ];

    public function kegiatan()
    {
        return $this->belongsTo(
            Kegiatan::class,
            'id_kegiatan',
            'id_kegiatan'
        );
    }

    public function subKegiatan()
    {
        return $this->hasMany(
            SubKegiatan::class,
            'id_indikator_kegiatan',
            'id_indikator_kegiatan'
        );
    }

    public function picStaff()
    {
        return $this->hasMany(
            PicStaff::class,
            'id_indikator_kegiatan',
            'id_indikator_kegiatan'
        );
    }

    public function scopeYangDitugaskanKe($queryIndikatorKegiatan, int $idPengguna)
    {
        return $queryIndikatorKegiatan->whereHas('picStaff', function ($queryPicStaff) use ($idPengguna) {
            $queryPicStaff->where('id_user', $idPengguna);
        });
    }
    public function scopeYangDitugaskanKeUnit($queryIndikatorKegiatan, int $idUnit)
    {
        return $queryIndikatorKegiatan->whereHas('kegiatan.indikatorProgram', function ($queryIndikatorProgram) use ($idUnit) {
            $queryIndikatorProgram->yangDitugaskanKeUnit($idUnit);
        });
    }

    public function scopeYangDapatDilihatOleh($queryIndikatorKegiatan, $pengguna)
    {
        return match ($pengguna->role) {
            'staff' => $queryIndikatorKegiatan->yangDitugaskanKe($pengguna->id_user),
            'asmen' => $queryIndikatorKegiatan->yangDitugaskanKeUnit($pengguna->id_unit),
            default => $queryIndikatorKegiatan, // manager melihat semua
        };
    }
}
