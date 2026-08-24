<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OrangTua extends Model {
    protected $table = 'orang_tua';
    protected $guarded = ['id'];
    protected $casts = [
        'tahun_lahir_ayah' => 'integer',
        'tahun_lahir_ibu' => 'integer',
        'tahun_lahir_wali' => 'integer',
    ];
    public function siswa() { return $this->belongsTo(Siswa::class); }
}
