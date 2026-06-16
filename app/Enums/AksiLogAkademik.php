<?php

namespace App\Enums;

enum AksiLogAkademik: string
{
    case TahunAjaranDibuat = 'tahun_ajaran_dibuat';
    case TahunAjaranDiubah = 'tahun_ajaran_diubah';
    case TahunAjaranDihapus = 'tahun_ajaran_dihapus';
    case TahunAjaranDiaktifkan = 'tahun_ajaran_diaktifkan';
    case SemesterGenapDimulai = 'semester_genap_dimulai';
    case SemesterGenapDiakhiri = 'semester_genap_diakhiri';
    case TahunAjaranDitutup = 'tahun_ajaran_ditutup';
    case DikembalikanKeGanjil = 'dikembalikan_ke_ganjil';
    case SemesterGenapDibukaKembali = 'semester_genap_dibuka_kembali';
    case TahunAjaranDibukaKembali = 'tahun_ajaran_dibuka_kembali';

    public function label(): string
    {
        return match ($this) {
            self::TahunAjaranDibuat => 'Tahun ajaran dibuat',
            self::TahunAjaranDiubah => 'Tahun ajaran diubah',
            self::TahunAjaranDihapus => 'Tahun ajaran dihapus',
            self::TahunAjaranDiaktifkan => 'Tahun ajaran diaktifkan',
            self::SemesterGenapDimulai => 'Semester genap dimulai',
            self::SemesterGenapDiakhiri => 'Semester genap diakhiri',
            self::TahunAjaranDitutup => 'Tahun ajaran ditutup',
            self::DikembalikanKeGanjil => 'Dikembalikan ke semester ganjil',
            self::SemesterGenapDibukaKembali => 'Semester genap dibuka kembali',
            self::TahunAjaranDibukaKembali => 'Tahun ajaran dibuka kembali',
        };
    }
}
