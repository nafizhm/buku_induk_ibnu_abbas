<?php

namespace App\Enums;

enum PendidikanTerakhir: string
{
    case SLTA = 'SLTA';
    case S1 = 'S1';
    case S2 = 'S2';
    case S3 = 'S3';
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
