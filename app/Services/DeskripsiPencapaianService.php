<?php

namespace App\Services;

use App\Enums\PredikatCapaian;
use App\Models\NilaiTujuanPembelajaran;
use Illuminate\Support\Collection;

class DeskripsiPencapaianService
{
    private const PREFIX_BAIK = 'menunjukkan capaian yang baik pada';

    private const PREFIX_BIMBINGAN = 'perlu bimbingan pada';

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
                PredikatCapaian::A => 'sangat '.$kataKerja,
                PredikatCapaian::B => $kataKerja,
                PredikatCapaian::C => 'cukup '.$kataKerja,
                PredikatCapaian::D => 'belum '.$kataKerja,
            };

            return (string) preg_replace($pattern, $pengganti, $deskripsi, 1);
        }

        return $deskripsi;
    }

    /**
     * @param  Collection<int, NilaiTujuanPembelajaran>  $nilaiTp
     */
    public function generatePencapaianRingkas(Collection $nilaiTp): ?string
    {
        if ($nilaiTp->isEmpty()) {
            return null;
        }

        $terurut = $nilaiTp->sortByDesc('nilai')->values();

        $tertinggi = $terurut->first();
        $terendah = $terurut->last();

        $baris = [
            self::PREFIX_BAIK.' '.$this->ekstrakKompetensi(
                $tertinggi->tujuanPembelajaran->deskripsi_kompetensi,
            ),
        ];

        $samaTp = $tertinggi->tujuan_pembelajaran_id === $terendah->tujuan_pembelajaran_id;
        $samaNilai = (float) $tertinggi->nilai === (float) $terendah->nilai;

        if (! $samaTp || ! $samaNilai) {
            $baris[] = self::PREFIX_BIMBINGAN.' '.$this->ekstrakKompetensi(
                $terendah->tujuanPembelajaran->deskripsi_kompetensi,
            );
        }

        return implode("\n", $baris);
    }

    private function ekstrakKompetensi(string $deskripsiKompetensi): string
    {
        $deskripsi = trim($deskripsiKompetensi);

        foreach (['Siswa mampu ', 'Siswa dapat ', 'Siswa '] as $prefix) {
            if (str_starts_with(mb_strtolower($deskripsi), mb_strtolower($prefix))) {
                return mb_substr($deskripsi, mb_strlen($prefix));
            }
        }

        return $deskripsi;
    }
}
