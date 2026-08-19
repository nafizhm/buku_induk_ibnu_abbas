<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pengguna extends Model
{
    use HasFactory;

    protected $table = 'users';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'username',
        'password',
        'email',
        'status',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }
}
