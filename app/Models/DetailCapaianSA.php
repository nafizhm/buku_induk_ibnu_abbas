<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailCapaianSA extends Model
{
     use HasFactory;

    protected $table   = 'detail_capaian_sa';
    public $timestamps = false;

    protected $fillable = [
        'id_capaian',
        'id_soal',
        'id_jawaban',
        'jawaban_essay',
    ];
}
