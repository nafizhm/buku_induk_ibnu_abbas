<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Rombel extends Model
{
    protected $table = 'rombel';

    protected $fillable = [
        'nama',
        'jenjang_kelas_id',
        'tahun_ajaran_id',
        'walas_id',
    ];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    public function jenjangKelas()
    {
        return $this->belongsTo(JenjangKelas::class);
    }

    public function penempatanSiswa()
    {
        return $this->hasMany(PenempatanSiswa::class);
    }

    public function walas()
    {
        return $this->belongsTo(Pengajar::class, 'walas_id');
    }

    public function jadwal()
    {
        return $this->hasManyThrough(JadwalMapel::class, MapelRombel::class, 'rombel_id', 'mapel_rombel_id');
    }

    public function mapelRombels()
    {
        return $this->hasMany(MapelRombel::class, 'rombel_id');
    }

    public function scopeFilterRombelAktif(Builder $query)
    {
        $tahunAktifId = TahunAjaran::where('is_active', 1)->value('id');

        return $query->where('tahun_ajaran_id', $tahunAktifId);
    }
    public function getSiswaCountAttribute()
    {
        return $this->penempatanSiswa()->count();
    }
    public function getSiswaList()
    {
        return $this->penempatanSiswa()
            ->with('siswa')
            ->get()
            ->pluck('siswa')
            ->sortBy('nama_lengkap')
            ->values();
    }
    public function countSiswaWithTagihan($komponenId, $tahunAjaranId = null)
    {
        $tahunAjaranId = $tahunAjaranId ?: $this->tahun_ajaran_id;

        return TagihanSiswa::whereIn('siswa_id', $this->penempatanSiswa()->pluck('siswa_id'))
            ->where('komponen_pembayaran_id', $komponenId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->count();
    }

    public function getSiswaWithoutTagihan($komponenId, $tahunAjaranId = null)
    {
        $tahunAjaranId = $tahunAjaranId ?: $this->tahun_ajaran_id;

        $siswaWithTagihan = TagihanSiswa::where('komponen_pembayaran_id', $komponenId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->pluck('siswa_id');

        return $this->penempatanSiswa()
            ->whereNotIn('siswa_id', $siswaWithTagihan)
            ->with('siswa')
            ->get()
            ->pluck('siswa');
    }
}
