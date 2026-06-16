<?php

namespace App\Enums;

enum KelompokMataPelajaran: string
{
    case Umum = 'umum';
    case Kejuruan = 'kejuruan';
    case MuatanLokal = 'muatan_lokal';

    public function label(): string
    {
        return match ($this) {
            self::Umum => 'Umum',
            self::Kejuruan => 'Kejuruan',
            self::MuatanLokal => 'Muatan Lokal',
        };
    }
}
