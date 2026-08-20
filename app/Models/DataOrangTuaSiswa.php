<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class DataOrangTuaSiswa extends Model { protected $table='data_orang_tua_siswa'; protected $guarded=['id']; protected $casts=['tanggal_lahir'=>'date:Y-m-d','alamat_sama_dengan_siswa'=>'boolean']; }
