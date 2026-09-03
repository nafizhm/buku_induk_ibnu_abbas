<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $table = 'siswa';
    protected $guarded = ['id'];
    protected $casts = [
        'tanggal_lahir' => 'date:Y-m-d', 'tanggal_masuk_sekolah' => 'date:Y-m-d',
        'tanggal_keluar' => 'date:Y-m-d',
        'berkebutuhan_khusus' => 'boolean', 'jarak_sekolah' => 'decimal:2', 'jarak_tempuh' => 'decimal:2',
        'lintang' => 'decimal:6', 'bujur' => 'decimal:6',
        'tinggi_badan' => 'decimal:2', 'berat_badan' => 'decimal:2', 'lingkar_kepala' => 'decimal:2',
    ];

    public function orangTua() { return $this->hasOne(OrangTua::class); }
    public function lampiran() { return $this->hasMany(LampiranSiswa::class); }
    public function kelas() { return $this->belongsTo(Kelas::class, 'kelas_id', 'id_kelas'); }
    public function prestasi() { return $this->hasMany(PrestasiSiswa::class); }
    public function beasiswa() { return $this->hasMany(BeasiswaSiswa::class); }
    public function pengguna()
    {
        return $this->belongsToMany(Pengguna::class, 'akun_siswa', 'siswa_id', 'user_id')
            ->withPivot('hubungan')
            ->using(AkunSiswa::class);
    }
}
