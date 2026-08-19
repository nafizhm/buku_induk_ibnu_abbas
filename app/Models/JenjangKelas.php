<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JenjangKelas extends Model
{
    protected $table = 'jenjang_kelas';

    protected $fillable = [
        'jenjang',
    ];

    public function rombels()
    {
        return $this->hasMany(Rombel::class);
    }
}
