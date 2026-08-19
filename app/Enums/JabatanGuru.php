<?php

namespace App\Enums;

enum JabatanGuru: string
{
    case KepalaSekolah = 'Kepala Sekolah';
    case WakilKepala = 'Wakil Kepala';
    case GuruMapel = 'Guru Mapel';
    case GuruBK = 'Guru BK';
    case WaliKelas = 'Wali Kelas';
    case Admin = 'Admin';
    case PENGAJAR_MAPEL = 'pengajar Mapel';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
