<?php

namespace App\Support;

use App\Models\SpmbPendaftaran;

class SpmbQuota
{
    public const LIMITS = [
        'SD' => ['Putra (Banin)' => 14, 'Putri (Banat)' => 16],
        'SMP' => ['Putra (Banin)' => 30],
    ];

    public static function availability(): array
    {
        $counts = SpmbPendaftaran::selectRaw('jenjang, jk, COUNT(*) as jumlah')->groupBy('jenjang', 'jk')->get();
        $result = [];
        foreach (self::LIMITS as $jenjang => $categories) {
            foreach ($categories as $jk => $limit) {
                $count = (int) ($counts->first(fn ($row) => $row->jenjang === $jenjang && $row->jk === $jk)?->jumlah ?? 0);
                $result[$jenjang][$jk] = ['remaining' => max(0, $limit - $count), 'limit' => $limit];
            }
        }

        return $result;
    }
}
