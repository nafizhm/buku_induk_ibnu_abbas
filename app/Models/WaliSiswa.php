<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class WaliSiswa extends Model { protected $table='wali_siswa'; protected $guarded=['id']; protected $casts=['tanggal_lahir'=>'date:Y-m-d']; }
