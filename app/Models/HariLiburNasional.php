<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HariLiburNasional extends Model
{
    use HasFactory;

    protected $table   = 'hari_libur_nasional';
    public $timestamps = false;

    protected $fillable = [
        'tanggal',
        'dalam_rangka',
    ];
}
