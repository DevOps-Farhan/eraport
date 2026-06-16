<?php

namespace App\Enums;

enum StatusRombel: string
{
    case Aktif = 'aktif';
    case Pindah = 'pindah';
    case Lulus = 'lulus';
    case Keluar = 'keluar';

    public function label(): string
    {
        return match ($this) {
            self::Aktif => 'Aktif',
            self::Pindah => 'Pindah',
            self::Lulus => 'Lulus',
            self::Keluar => 'Keluar',
        };
    }
}
