<?php

namespace App\Services;

use App\Enums\StatusTahunAjaran;
use App\Models\TahunAjaran;

class KonteksAkademik
{
    public function tahunAjaranAktif(int $cabangSekolahId): ?TahunAjaran
    {
        return TahunAjaran::query()
            ->with(['semester', 'semesterBerjalan'])
            ->where('cabang_sekolah_id', $cabangSekolahId)
            ->where('status', StatusTahunAjaran::Aktif)
            ->first();
    }
}
