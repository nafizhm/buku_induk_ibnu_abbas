<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BeasiswaSiswa extends Model {
    protected $table='beasiswa_siswa';
    protected $guarded=['id'];
    protected $casts=['tahun_mulai'=>'integer','tahun_selesai'=>'integer'];
    public function siswa() { return $this->belongsTo(Siswa::class); }
}
