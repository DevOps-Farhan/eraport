<?php

namespace App\Enums;

enum TingkatKelas: string
{
    case X = 'X';
    case XI = 'XI';
    case XII = 'XII';

    public function label(): string
    {
        return $this->value;
    }
}
