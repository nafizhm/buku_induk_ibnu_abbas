<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tugas extends Model
{
    use HasFactory;

    protected $table   = 'tugas';
    public $timestamps = false;

    protected $fillable = [
        'id_guru',
        'nama',
        'tgl_mulai',
        'tgl_akhir',
        'jam_mulai',
        'jam_akhir',
        'file',
    ];

    public function guru()
    {
        return $this->belongsTo(Pengguna::class, 'id_guru');
    }
}
