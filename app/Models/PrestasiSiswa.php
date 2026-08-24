<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class PrestasiSiswa extends Model {
    protected $table='prestasi_siswa';
    protected $guarded=['id'];
    protected $casts=['tahun'=>'integer'];
    public function siswa() { return $this->belongsTo(Siswa::class); }
}
