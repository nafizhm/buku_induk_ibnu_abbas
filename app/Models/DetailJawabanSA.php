<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJawabanSA extends Model
{
    use HasFactory;

    protected $table   = 'detail_jawaban_sa';
    public $timestamps = false;

    protected $fillable = [
        'id_soal',
        'jawaban',
    ];

    public function soal()
    {
        return $this->belongsTo(SoalSupervisiAkademik::class, 'id_soal');
    }
}
