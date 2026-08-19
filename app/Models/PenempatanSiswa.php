<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenempatanSiswa extends Model
{
    protected $table = 'penempatan_siswa';

    protected $fillable = [
        'siswa_id',
        'rombel_id',
    ];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }

    public function rombel()
    {
        return $this->belongsTo(Rombel::class);
    }
}
