<?php

namespace App\Enums;

enum FaseKelas: string
{
    case E = 'E';
    case F = 'F';

    public function label(): string
    {
        return $this->value;
    }
}
