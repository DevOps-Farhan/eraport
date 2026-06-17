<?php

namespace App\Http\Controllers\Guru\Concerns;

use App\Enums\StatusSemester;
use App\Models\PenugasanMengajar;
use App\Models\Semester;
use App\Models\TahunAjaran;
use App\Models\TujuanPembelajaran;
use App\Services\KonteksAkademik;
use Illuminate\Database\Eloquent\Collection;

trait MenanganiPenugasanGuru
{
    protected function cabangSekolahId(): int
    {
        $cabangId = auth()->user()->cabang_sekolah_id;

        if (! $cabangId) {
            abort(403, 'Guru tidak terhubung ke cabang sekolah.');
        }

        return $cabangId;
    }

    protected function tahunAjaranAktif(): ?TahunAjaran
    {
        return app(KonteksAkademik::class)->tahunAjaranAktif($this->cabangSekolahId());
    }

    protected function semesterBerjalan(): ?Semester
    {
        return $this->tahunAjaranAktif()?->semesterBerjalan;
    }

    protected function semesterDapatDiedit(): bool
    {
        $semester = $this->semesterBerjalan();

        return $semester && $semester->status === StatusSemester::Berjalan;
    }

    /**
     * @return Collection<int, PenugasanMengajar>
     */
    protected function penugasanGuru(): Collection
    {
        $tahunAjaran = $this->tahunAjaranAktif();

        if (! $tahunAjaran) {
            return new Collection;
        }

        return PenugasanMengajar::query()
            ->with(['mataPelajaran', 'kelas'])
            ->where('guru_id', auth()->id())
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->orderBy('id')
            ->get();
    }

    protected function pastikanPenugasanGuru(PenugasanMengajar $penugasan): PenugasanMengajar
    {
        $tahunAjaran = $this->tahunAjaranAktif();

        if (! $tahunAjaran
            || $penugasan->guru_id !== auth()->id()
            || $penugasan->tahun_ajaran_id !== $tahunAjaran->id) {
            abort(404);
        }

        return $penugasan->load(['mataPelajaran', 'kelas']);
    }

    protected function pastikanTujuanPembelajaranGuru(TujuanPembelajaran $tujuanPembelajaran): TujuanPembelajaran
    {
        $tujuanPembelajaran->load('penugasanMengajar');
        $this->pastikanPenugasanGuru($tujuanPembelajaran->penugasanMengajar);

        return $tujuanPembelajaran;
    }
}
