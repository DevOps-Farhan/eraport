<?php

namespace App\Http\Controllers\AdminCabang\Concerns;

use App\Models\TahunAjaran;
use App\Services\KonteksAkademik;

trait MenanganiScopeCabang
{
    protected function cabangSekolahId(): int
    {
        $cabangId = auth()->user()->cabang_sekolah_id;

        if (! $cabangId) {
            abort(403, 'Admin cabang tidak terhubung ke cabang sekolah.');
        }

        return $cabangId;
    }

    protected function pastikanMilikCabang(object $model, string $kolom = 'cabang_sekolah_id'): void
    {
        if ($model->{$kolom} !== $this->cabangSekolahId()) {
            abort(404);
        }
    }

    protected function tahunAjaranAktif(): TahunAjaran
    {
        $tahunAjaran = app(KonteksAkademik::class)->tahunAjaranAktif($this->cabangSekolahId());

        if (! $tahunAjaran) {
            abort(403, 'Tidak ada tahun ajaran aktif. Aktifkan tahun ajaran terlebih dahulu.');
        }

        return $tahunAjaran;
    }

    protected function pastikanTahunAjaranAktif(TahunAjaran $tahunAjaran): void
    {
        if ($tahunAjaran->cabang_sekolah_id !== $this->cabangSekolahId() || ! $tahunAjaran->isAktif()) {
            abort(404);
        }
    }
}
