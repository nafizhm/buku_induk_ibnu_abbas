<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_lahir' => 'date:Y-m-d', 'tanggal_masuk_sekolah' => 'date:Y-m-d',
        'berkebutuhan_khusus' => 'boolean', 'jarak_sekolah' => 'decimal:2',
        'tinggi_badan' => 'decimal:2', 'berat_badan' => 'decimal:2', 'lingkar_kepala' => 'decimal:2',
    ];

    public function orangTua() { return $this->hasMany(DataOrangTuaSiswa::class); }
    public function ayah() { return $this->hasOne(DataOrangTuaSiswa::class)->where('jenis', 'Ayah'); }
    public function ibu() { return $this->hasOne(DataOrangTuaSiswa::class)->where('jenis', 'Ibu'); }
    public function wali() { return $this->hasOne(WaliSiswa::class); }
    public function lampiran() { return $this->hasMany(LampiranSiswa::class); }
    public function kelas() { return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas'); }
}
