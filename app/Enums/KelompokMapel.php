<?php

namespace App\Enums;

enum KelompokMapel: string
{
    case A = 'A';
    case B = 'B';

    public function label(): string
    {
        return match ($this) {
            self::A => 'Mata Pelajaran Umum',
            self::B => 'Muatan Lokal',
        };
    }

    public static function options(): array
    {
        return array_map(fn($item) => [
            'value' => $item->value,
            'label' => $item->label(),
        ], self::cases());
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}