<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $table = 'tahun_ajaran';

    protected $fillable = [
        'tahun',
        'is_active',
    ];

    public function rombels()
    {
        return $this->hasMany(Rombel::class);
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalMapel::class, 'tahun_ajaran_id');
    }
}
