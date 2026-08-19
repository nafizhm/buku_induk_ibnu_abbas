<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalRaporKinerjaGuru extends Model
{
    use HasFactory;

    protected $table   = 'soal_rkg';
    public $timestamps = false;

    protected $fillable = [
        'soal',
        'jenis',
    ];

    public function detailJawaban()
    {
        return $this->hasMany(DetailJawabanRKG::class, 'id_soal');
    }
}
