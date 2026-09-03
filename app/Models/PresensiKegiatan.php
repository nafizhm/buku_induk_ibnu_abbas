<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresensiKegiatan extends Model
{
    protected $table = 'presensi_kegiatan';
    protected $guarded = ['id'];
    protected $casts = ['qr_diambil_at' => 'datetime'];

    public function kegiatan() { return $this->belongsTo(Kegiatan::class); }
    public function siswa() { return $this->belongsTo(Siswa::class); }
}
