<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class SpmbPendaftaran extends Model
{
    protected $table = 'spmb_pendaftaran';

    protected $fillable = ['nama', 'jk', 'ortu', 'wa', 'bukti_path', 'bukti_mime'];

    protected $attributes = ['status' => 'formulir'];

    protected $casts = ['wa_dikirim_at' => 'datetime', 'selesai_at' => 'datetime'];

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
