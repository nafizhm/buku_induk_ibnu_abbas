<?php

namespace App\Models;

use App\Enums\JabatanGuru;
use App\Enums\PendidikanTerakhir;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pengajar extends Authenticatable
{
    protected $table = 'pengajar';

    protected $fillable = [
        'kode_pengajar',
        'nip',
        'nama',
        'password',
        'jenis_kelamin',
        'jabatan',
        'alamat',
        'tempat_lahir',
        'tanggal_lahir',
        'pendidikan_terakhir',
        'no_telepon',
        'is_active',
    ];

    protected $casts = [
        'jabatan' => JabatanGuru::class,
        'pendidikan_terakhir' => PendidikanTerakhir::class,
    ];
    protected $hidden = [
        'password',
    ];
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeNotActive($query)
    {
        return $query->where('is_active', false);
    }

    public function jadwal()
    {
        return $this->hasMany(JadwalMapel::class, 'guru_id');
    }
}
