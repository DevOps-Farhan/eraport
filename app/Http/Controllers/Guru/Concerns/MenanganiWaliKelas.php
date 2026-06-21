<?php

namespace App\Http\Controllers\Guru\Concerns;

use App\Enums\StatusRombel;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Services\KonteksAkademik;

trait MenanganiWaliKelas
{
    protected function cabangSekolahId(): int
    {
        $cabangId = auth()->user()->cabang_sekolah_id;

        if (! $cabangId) {
            abort(403, 'Guru tidak terhubung ke cabang sekolah.');
        }

        return $cabangId;
    }

    protected function daftarKelasWali()
    {
        $tahunAjaran = app(KonteksAkademik::class)->tahunAjaranAktif($this->cabangSekolahId());

        if (! $tahunAjaran) {
            return collect();
        }

        return Kelas::query()
            ->with(['jurusan', 'tahunAjaran'])
            ->withCount(['rombelSiswa as jumlah_siswa' => function ($query) {
                $query->where('status', StatusRombel::Aktif);
            }])
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('wali_kelas_id', auth()->id())
            ->get();
    }

    protected function pastikanAksesKelas(Kelas $kelas): void
    {
        $tahunAjaran = app(KonteksAkademik::class)->tahunAjaranAktif($this->cabangSekolahId());

        if (! $tahunAjaran ||
            $kelas->tahun_ajaran_id !== $tahunAjaran->id ||
            $kelas->wali_kelas_id !== auth()->id()
        ) {
            abort(404);
        }
    }

    protected function pastikanSiswaDiKelasWali(Siswa $siswa, Kelas $kelas): Siswa
    {
        $this->pastikanAksesKelas($kelas);

        $adaDiKelas = $siswa->rombelSiswa()
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
            ->where('status', StatusRombel::Aktif)
            ->exists();

        if (! $adaDiKelas || $siswa->cabang_sekolah_id !== $this->cabangSekolahId()) {
            abort(404);
        }

        return $siswa;
    }
}
