<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Builder;

class Siswa extends Authenticatable
{
    use HasApiTokens;
    protected $table = 'siswa';
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $appends = ['kelas'];
    protected $fillable = [
        'nipd',
        'nisn',
        'nfc_uid',
        'password',
        'nama_lengkap',
        'nama_panggilan',
        'jenis_kelamin',
        'tempat_lahir',
        'tanggal_lahir',
        'agama',
        'kewarganegaraan',
        'jumlah_saudara',
        'bahasa_rumah',
        'golongan_darah',
        'alamat',
        'no_telepon_rumah',
        'tinggal_dengan',
        'jarak_sekolah',
        'foto',
        'kesanggupan_spp'
    ];

    public function penempatanSiswa()
    {
        return $this->hasMany(PenempatanSiswa::class);
    }

    public function tagihanSiswa()
    {
        return $this->hasMany(TagihanSiswa::class);
    }

    public function kelulusanSiswa()
    {
        return $this->hasMany(KelulusanSiswa::class);
    }

    public function penempatanAktif()
    {
        $tahunAktifId = TahunAjaran::where('is_active', 1)->value('id');

        return $this->penempatanSiswa()
            ->whereHas('rombel', function ($q) use ($tahunAktifId) {
                $q->where('tahun_ajaran_id', $tahunAktifId);
            });
    }

    public function scopeHasKelas(Builder $query)
    {
        $tahunAktifId = TahunAjaran::where('is_active', 1)->value('id');

        return $query->whereHas('penempatanSiswa.rombel', function ($query) use ($tahunAktifId) {
            $query->where('tahun_ajaran_id', $tahunAktifId);
        });
    }

    public function scopeHasNoKelas(Builder $query)
    {
        $tahunAktifId = TahunAjaran::where('is_active', 1)->value('id');

        return $query->whereHas('penempatanSiswa.rombel', function ($subQuery) use ($tahunAktifId) {
            $subQuery->where('tahun_ajaran_id', '!=', $tahunAktifId);
        });
    }

    public function scopeIsCalon(Builder $query)
    {
        return $query->where(function ($query) {
            $query->whereDoesntHave('penempatanSiswa');
        });
    }

    public function scopeIsLulus(Builder $query)
    {
        return $query->whereHas('kelulusanSiswa');
    }

    public function scopeIsBelumLulus(Builder $query)
    {
        return $query->whereDoesntHave('kelulusanSiswa');
    }

    public function getCurrentRombel()
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        return $this->penempatanSiswa()
            ->whereHas('rombel', function ($query) use ($tahunAktif) {
                $query->where('tahun_ajaran_id', $tahunAktif?->id);
            })
            ->with('rombel')
            ->first()?->rombel;
    }

    public function hasTagihanForKomponen($komponenId, $tahunAjaranId = null)
    {
        $tahunAjaranId = $tahunAjaranId ?: TahunAjaran::where('is_active', true)->first()?->id;

        return $this->tagihanSiswa()
            ->where('komponen_pembayaran_id', $komponenId)
            ->where('tahun_ajaran_id', $tahunAjaranId)
            ->exists();
    }

    public function getTotalTagihanUangPangkal()
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        return $this->tagihanSiswa()
            ->whereHas('jenisPembayaran', function ($q) {
                $q->where('nama', JenisPembayaran::UANG_PANGKAL);
            })
            ->where('tahun_ajaran_id', $tahunAktif?->id)
            ->sum('nominal');
    }

    public function getTotalTerbayarUangPangkal()
    {
        $tahunAktif = TahunAjaran::where('is_active', true)->first();

        return $this->tagihanSiswa()
            ->whereHas('jenisPembayaran', function ($q) {
                $q->where('nama', JenisPembayaran::UANG_PANGKAL);
            })
            ->where('tahun_ajaran_id', $tahunAktif?->id)
            ->get()
            ->sum('total_dibayar');
    }

    public function getKelasAttribute()
    {
        $rombel = $this->getCurrentRombel();
        return $rombel ? $rombel->jenjangKelas->jenjang . ' ' . $rombel->nama : null;
    }

    public function getTanggalLahirAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->translatedFormat('d F Y');
    }

    public function orangTua()
    {
        return $this->hasOne(OrangTua::class);
    }

    public function wali()
    {
        return $this->hasOne(Wali::class);
    }

    public function kesehatanSiswa()
    {
        return $this->hasMany(KesehatanSiswa::class);
    }

    public function beasiswa()
    {
        return $this->hasOne(Beasiswa::class);
    }

    public function riwayatPendidikan()
    {
        return $this->hasOne(RiwayatPendidikan::class);
    }

    public function pindahSiswa()
    {
        return $this->hasOne(PindahSiswa::class);
    }

    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'siswa_id');
    }

    public function tahfidzh()
    {
        return $this->hasMany(Tahfidzh::class, 'id_siswa');
    }
}
