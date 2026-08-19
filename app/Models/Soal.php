<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $table   = 'soal';
    public $timestamps = false;

    protected $fillable = [
        'id_guru',
        'nama',
    ];

    public function guru()
    {
        return $this->belongsTo(Pengguna::class, 'id_guru');
    }
}
