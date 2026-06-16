<?php

namespace App\Enums;

enum StatusTahunAjaran: string
{
    case Draft = 'draft';
    case Aktif = 'aktif';
    case Ditutup = 'ditutup';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Aktif => 'Aktif',
            self::Ditutup => 'Ditutup',
        };
    }
}
