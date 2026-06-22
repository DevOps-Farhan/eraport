<?php

namespace App\Http\Controllers\Pembina\Concerns;

use App\Models\Ekstrakurikuler;
use App\Models\PenugasanPembina;
use App\Services\KonteksAkademik;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

trait MenanganiPembina
{
    /**
     * @return Collection<int, Ekstrakurikuler>
     */
    protected function daftarEkstrakurikulerPembina(): Collection
    {
        $tahunAjaranAktif = app(KonteksAkademik::class)->tahunAjaranAktif(Auth::user()->cabang_sekolah_id);

        if (! $tahunAjaranAktif) {
            return new Collection();
        }

        return Ekstrakurikuler::query()
            ->whereHas('penugasanPembina', function ($query) use ($tahunAjaranAktif) {
                $query->where('pembina_id', Auth::id())
                    ->where('tahun_ajaran_id', $tahunAjaranAktif->id);
            })
            ->withCount(['anggotaEkstrakurikuler as anggota_count' => function ($query) use ($tahunAjaranAktif) {
                $query->where('tahun_ajaran_id', $tahunAjaranAktif->id);
            }])
            ->orderBy('nama')
            ->get();
    }

    protected function pastikanAksesEkstrakurikuler(Ekstrakurikuler $ekstrakurikuler): void
    {
        $tahunAjaranAktif = app(KonteksAkademik::class)->tahunAjaranAktif(Auth::user()->cabang_sekolah_id);

        if (! $tahunAjaranAktif) {
            abort(403, 'Tahun ajaran belum diatur.');
        }

        $penugasan = PenugasanPembina::query()
            ->where('pembina_id', Auth::id())
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->exists();

        if (! $penugasan) {
            abort(403, 'Anda tidak ditugaskan sebagai pembina untuk ekstrakurikuler ini.');
        }
    }
}
