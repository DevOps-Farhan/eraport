<?php

namespace App\Services;

use App\Enums\AksiLogAkademik;
use App\Models\LogAkademik;

class LogAkademikService
{
    public function catat(
        int $cabangSekolahId,
        AksiLogAkademik $aksi,
        ?int $tahunAjaranId = null,
        ?string $keterangan = null,
    ): void {
        LogAkademik::query()->create([
            'cabang_sekolah_id' => $cabangSekolahId,
            'tahun_ajaran_id' => $tahunAjaranId,
            'pengguna_id' => auth()->id(),
            'aksi' => $aksi,
            'keterangan' => $keterangan,
            'created_at' => now(),
        ]);
    }
}
