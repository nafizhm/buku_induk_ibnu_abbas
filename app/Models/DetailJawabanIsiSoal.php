<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJawabanIsiSoal extends Model
{
    use HasFactory;

    protected $table   = 'detail_jawaban_isi_soal';
    public $timestamps = false;

    protected $fillable = [
        'id_isi_soal',
        'jawaban',
    ];

    public function soal()
    {
        return $this->belongsTo(IsiSoal::class, 'id_isi_soal');
    }
}
