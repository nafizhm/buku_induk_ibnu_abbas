<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    protected $table = 'media';
    protected $fillable = ['jenis', 'kategori', 'judul', 'path', 'youtube_code'];

    public function getFotoUrlAttribute(): string
    {
        return str_starts_with($this->path ?? '', 'assets/spmb/galeri/')
            ? asset($this->path) : route('media.foto', $this->id);
    }
}
