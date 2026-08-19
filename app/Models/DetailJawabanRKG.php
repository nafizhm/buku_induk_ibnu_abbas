<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailJawabanRKG extends Model
{
    use HasFactory;

    protected $table   = 'detail_jawaban_rkg';
    public $timestamps = false;

    protected $fillable = [
        'id_soal',
        'jawaban',
    ];

    public function soal()
    {
        return $this->belongsTo(SoalRaporKinerjaGuru::class, 'id_soal');
    }
}
