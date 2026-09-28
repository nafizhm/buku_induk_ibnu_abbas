<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SpmbLampiran extends Model
{
    protected $table = 'spmb_lampiran';

    protected $guarded = ['id'];

    public const DOKUMEN = [
        'akta_kelahiran' => ['label' => 'Fotocopy Akta Kelahiran', 'required' => true],
        'kartu_keluarga' => ['label' => 'Fotocopy Kartu Keluarga', 'required' => true],
        'ktp_ayah' => ['label' => 'Foto KTP Ayah', 'required' => true],
        'ktp_ibu' => ['label' => 'Foto KTP Ibu', 'required' => true],
        'ijazah_tk' => ['label' => 'Fotocopy Ijazah TK', 'required' => false],
        'pas_foto' => ['label' => 'Pas Foto', 'required' => true],
    ];
}
