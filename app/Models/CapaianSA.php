<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CapaianSA extends Model
{
    use HasFactory;

    protected $table   = 'capaian_sa';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'id_guru',
    ];

    public function guru()
    {
        return $this->belongsTo(Pengguna::class, 'id_guru');
    }
}
