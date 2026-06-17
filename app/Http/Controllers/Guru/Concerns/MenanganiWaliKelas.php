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

    protected function kelasWali(): ?Kelas
    {
        $tahunAjaran = app(KonteksAkademik::class)->tahunAjaranAktif($this->cabangSekolahId());

        if (! $tahunAjaran) {
            return null;
        }

        return Kelas::query()
            ->with(['jurusan', 'tahunAjaran'])
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->where('wali_kelas_id', auth()->id())
            ->first();
    }

    protected function pastikanKelasWali(): Kelas
    {
        $kelas = $this->kelasWali();

        if (! $kelas) {
            abort(404);
        }

        return $kelas;
    }

    protected function pastikanSiswaDiKelasWali(Siswa $siswa): Siswa
    {
        $kelas = $this->pastikanKelasWali();

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
