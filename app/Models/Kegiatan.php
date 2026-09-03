<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $table = 'kegiatan';
    protected $guarded = ['id'];
    protected $casts = ['tgl_kegiatan' => 'date:Y-m-d'];

    public function presensi()
    {
        return $this->hasMany(PresensiKegiatan::class);
    }
}
