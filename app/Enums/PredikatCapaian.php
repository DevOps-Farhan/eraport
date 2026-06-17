<?php

namespace App\Enums;

enum PredikatCapaian: string
{
    case SB = 'SB';
    case B = 'B';
    case C = 'C';
    case D = 'D';

    public function label(): string
    {
        return match ($this) {
            self::SB => 'Sangat Baik',
            self::B => 'Baik',
            self::C => 'Cukup',
            self::D => 'Kurang',
        };
    }

    public static function dariNilai(float $nilai): self
    {
        return match (true) {
            $nilai >= 90 => self::SB,
            $nilai >= 80 => self::B,
            $nilai >= 70 => self::C,
            default => self::D,
        };
    }
}
