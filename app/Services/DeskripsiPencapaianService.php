<?php

namespace App\Services;

use App\Enums\PredikatCapaian;

class DeskripsiPencapaianService
{
    /** @var list<string> */
    private const KATA_KERJA = [
        'mampu',
        'memahami',
        'menjelaskan',
        'menganalisis',
        'menerapkan',
        'mendemonstrasikan',
    ];

    public function generate(string $deskripsiKompetensi, float $nilai): array
    {
        $predikat = PredikatCapaian::dariNilai($nilai);
        $deskripsi = $this->sesuaikanKataKerja($deskripsiKompetensi, $predikat);

        return [
            'predikat' => $predikat,
            'deskripsi_pencapaian' => $deskripsi,
        ];
    }

    private function sesuaikanKataKerja(string $deskripsi, PredikatCapaian $predikat): string
    {
        foreach (self::KATA_KERJA as $kataKerja) {
            $pattern = '/\b'.preg_quote($kataKerja, '/').'\b/ui';

            if (! preg_match($pattern, $deskripsi)) {
                continue;
            }

            $pengganti = match ($predikat) {
                PredikatCapaian::SB => 'sangat '.$kataKerja,
                PredikatCapaian::B => $kataKerja,
                PredikatCapaian::C => 'cukup '.$kataKerja,
                PredikatCapaian::D => 'belum '.$kataKerja,
            };

            return (string) preg_replace($pattern, $pengganti, $deskripsi, 1);
        }

        return $deskripsi;
    }
}
