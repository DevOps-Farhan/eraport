<?php

namespace App\Services;

use App\Enums\PredikatCapaian;
use App\Enums\StatusRombel;
use App\Models\NilaiTujuanPembelajaran;
use App\Models\NilaiUjian;
use App\Models\PenugasanMengajar;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TujuanPembelajaran;
use Illuminate\Support\Collection;

class NilaiRaportService
{
    public function hitungRataTp(PenugasanMengajar $penugasan, Siswa $siswa, Semester $semester): ?float
    {
        $tpIds = TujuanPembelajaran::query()
            ->where('penugasan_mengajar_id', $penugasan->id)
            ->pluck('id');

        if ($tpIds->isEmpty()) {
            return null;
        }

        $nilaiTp = NilaiTujuanPembelajaran::query()
            ->whereIn('tujuan_pembelajaran_id', $tpIds)
            ->where('siswa_id', $siswa->id)
            ->where('semester_id', $semester->id)
            ->pluck('nilai');

        if ($nilaiTp->isEmpty()) {
            return null;
        }

        return round((float) $nilaiTp->avg(), 2);
    }

    public function hitungNilaiUjian(PenugasanMengajar $penugasan, Siswa $siswa, Semester $semester): ?float
    {
        $nilai = NilaiUjian::query()
            ->where('penugasan_mengajar_id', $penugasan->id)
            ->where('siswa_id', $siswa->id)
            ->where('semester_id', $semester->id)
            ->value('nilai');

        return $nilai !== null ? (float) $nilai : null;
    }

    public function hitungNilaiAkhir(?float $rataTp, ?float $nilaiUjian): ?float
    {
        if ($rataTp === null || $nilaiUjian === null) {
            return null;
        }

        return round($rataTp * 0.6 + $nilaiUjian * 0.4, 2);
    }

    public function ringkasanMapel(PenugasanMengajar $penugasan, Siswa $siswa, Semester $semester): array
    {
        $rataTp = $this->hitungRataTp($penugasan, $siswa, $semester);
        $nilaiUjian = $this->hitungNilaiUjian($penugasan, $siswa, $semester);
        $nilaiAkhir = $this->hitungNilaiAkhir($rataTp, $nilaiUjian);

        return [
            'rata_tp' => $rataTp,
            'nilai_ujian' => $nilaiUjian,
            'nilai_akhir' => $nilaiAkhir,
            'predikat' => $nilaiAkhir !== null ? PredikatCapaian::dariNilai($nilaiAkhir) : null,
        ];
    }

    /**
     * @return Collection<int, Siswa>
     */
    public function siswaAktifKelas(PenugasanMengajar $penugasan): Collection
    {
        return Siswa::query()
            ->whereHas('rombelSiswa', function ($query) use ($penugasan) {
                $query->where('kelas_id', $penugasan->kelas_id)
                    ->where('tahun_ajaran_id', $penugasan->tahun_ajaran_id)
                    ->where('status', StatusRombel::Aktif);
            })
            ->orderBy('nama')
            ->get();
    }
}
