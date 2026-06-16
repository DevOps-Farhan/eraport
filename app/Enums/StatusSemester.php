<?php

namespace App\Enums;

enum StatusSemester: string
{
    case BelumDimulai = 'belum_dimulai';
    case Berjalan = 'berjalan';
    case Selesai = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::BelumDimulai => 'Belum Dimulai',
            self::Berjalan => 'Berjalan',
            self::Selesai => 'Selesai',
        };
    }
}
