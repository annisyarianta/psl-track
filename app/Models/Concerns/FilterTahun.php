<?php

namespace App\Models\Concerns;

trait FilterTahun
{
    public function scopeFilterTahun($query, $tahunDipilih)
    {
        if ($tahunDipilih === 'semua') {
            return $query;
        }

        return $query->whereHas($this->relasiTahun, function ($queryTahun) use ($tahunDipilih) {
            $queryTahun->where('tahun', $tahunDipilih);
        });
    }
}