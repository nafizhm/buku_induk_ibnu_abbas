<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Kelas extends Model { protected $table='kelas'; protected $primaryKey='id_kelas'; protected $guarded=['id_kelas']; public function siswa(){return $this->hasMany(Siswa::class,'kelas_id','id_kelas');} }
