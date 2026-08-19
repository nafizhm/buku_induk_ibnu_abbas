<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoalSupervisiAkademik extends Model
{
    use HasFactory;

    protected $table   = 'soal_sa';
    public $timestamps = false;

    protected $fillable = [
        'soal',
        'jenis',
    ];

     public function detailJawaban()
    {
        return $this->hasMany(DetailJawabanSA::class, 'id_soal');
    }
}
