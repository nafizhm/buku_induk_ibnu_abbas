<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IsiSoal extends Model
{
    use HasFactory;

    protected $table   = 'isi_soal';
    public $timestamps = false;

    protected $fillable = [
        'id_soal',
        'soal',
        'jenis',
        'foto',
    ];

    public function detailJawaban()
    {
        return $this->hasMany(DetailJawabanIsiSoal::class, 'id_isi_soal');
    }
}
