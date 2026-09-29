<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SpmbPendaftaran extends Model
{
    protected $table = 'spmb_pendaftaran';

    protected $fillable = ['nama', 'jk', 'jenjang', 'ortu', 'wa', 'bukti_path', 'bukti_mime'];

    protected $attributes = ['status' => 'formulir'];

    protected $casts = ['wa_dikirim_at' => 'datetime', 'selesai_at' => 'datetime',
        'pernyataan_data' => 'array', 'pernyataan_at' => 'datetime',
        'wawancara_data' => 'array', 'wawancara_at' => 'datetime'];

    public const FILTERS = [
        'semua' => 'Total Formulir',
        'wa' => 'Sudah Dikirim WA',
        'proses' => 'Proses Isi Formulir',
        'lengkap' => 'Data & Lampiran Lengkap',
    ];

    public function scopeStatistik(Builder $query, string $filter): Builder
    {
        if ($filter === 'wa') {
            $query->whereNotNull('wa_dikirim_at');
        } elseif ($filter === 'proses') {
            $query->where('status', 'isi formulir');
        } elseif ($filter === 'lengkap') {
            $query->where('status', 'selesai');
            foreach (SpmbLampiran::DOKUMEN as $jenis => $dokumen) {
                if ($dokumen['required']) {
                    $query->whereHas('lampiran', fn (Builder $lampiran) => $lampiran->where('jenis', $jenis));
                }
            }
        }

        return $query;
    }

    public function lampiran()
    {
        return $this->hasMany(SpmbLampiran::class);
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    protected static function booted(): void
    {
        static::creating(function (self $pendaftaran) {
            do {
                $token = Str::random(10);
            } while (self::where('token', $token)->exists());
            $pendaftaran->token = $token;
        });
    }
}
