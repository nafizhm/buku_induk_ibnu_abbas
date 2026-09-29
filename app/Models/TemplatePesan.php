<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TemplatePesan extends Model
{
    protected $table = 'template_pesan';

    protected $fillable = ['nama', 'penggunaan', 'isi'];

    public function render(SpmbPendaftaran $pendaftaran): string
    {
        return strtr($this->isi, [
            '[[link]]' => route('spmb.formulir', ['token' => $pendaftaran->token]),
            '[[nama]]' => $pendaftaran->nama,
            '[[ortu]]' => $pendaftaran->ortu,
            '[[jenjang]]' => $pendaftaran->jenjang,
        ]);
    }
}
