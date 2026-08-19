<?php

namespace App\Models;

use App\Enums\KelompokMapel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class MataPelajaran extends Model
{
    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'kode_mapel',
        'nama_mapel',
        'singkatan',
        'durasi',
        'kkm',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function mapelRombel(): HasMany
    {
        return $this->hasMany(MapelRombel::class, 'mata_pelajaran_id');
    }

    public function jadwal(): HasManyThrough
    {
        return $this->hasManyThrough(JadwalMapel::class, MapelRombel::class, 'mata_pelajaran_id', 'mapel_rombel_id');
    }

    public function nilai(): HasMany
    {
        return $this->hasMany(NilaiSiswa::class, 'mata_pelajaran_id');
    }
}
