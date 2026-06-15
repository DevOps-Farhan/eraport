<?php

namespace App\Enums;

enum PeranPengguna: string
{
    case AdminPusat = 'admin_pusat';
    case AdminCabang = 'admin_cabang';
    case Guru = 'guru';
    case PembinaEkstrakurikuler = 'pembina_ekstrakurikuler';

    public function label(): string
    {
        return match ($this) {
            self::AdminPusat => 'Admin Pusat',
            self::AdminCabang => 'Admin Cabang',
            self::Guru => 'Guru',
            self::PembinaEkstrakurikuler => 'Pembina Ekstrakurikuler',
        };
    }
}
