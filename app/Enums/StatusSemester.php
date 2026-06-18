<?php

namespace App\Enums;

enum StatusSemester: string
{
    case BelumDimulai = 'belum_dimulai';
    case Berjalan = 'berjalan';
    case Ditangguhkan = 'ditangguhkan';
    case Selesai = 'selesai';

    public function label(): string
    {
        return match ($this) {
            self::BelumDimulai => 'Belum Dimulai',
            self::Berjalan => 'Berjalan',
            self::Ditangguhkan => 'Ditangguhkan',
            self::Selesai => 'Selesai',
        };
    }
}
