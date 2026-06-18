<?php

namespace App\Services;

use App\Enums\AksiLogAkademik;
use App\Enums\JenisSemester;
use App\Enums\StatusSemester;
use App\Enums\StatusTahunAjaran;
use App\Models\AbsensiSiswa;
use App\Models\CatatanWaliKelas;
use App\Models\NilaiTujuanPembelajaran;
use App\Models\NilaiUjian;
use App\Models\PencapaianTp;
use App\Models\PenugasanMengajar;
use App\Models\PenugasanPembina;
use App\Models\RombelSiswa;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TahunAjaranService
{
    public function __construct(
        private LogAkademikService $logAkademikService,
    ) {}

    public function aktifkan(TahunAjaran $tahunAjaran): void
    {
        if (! $tahunAjaran->isDraft()) {
            throw ValidationException::withMessages([
                'tahun_ajaran' => 'Hanya tahun ajaran berstatus draft yang dapat diaktifkan.',
            ]);
        }

        $sudahAdaAktif = TahunAjaran::query()
            ->where('cabang_sekolah_id', $tahunAjaran->cabang_sekolah_id)
            ->where('status', StatusTahunAjaran::Aktif)
            ->exists();

        if ($sudahAdaAktif) {
            throw ValidationException::withMessages([
                'tahun_ajaran' => 'Cabang ini masih memiliki tahun ajaran aktif. Tutup terlebih dahulu sebelum mengaktifkan yang baru.',
            ]);
        }

        DB::transaction(function () use ($tahunAjaran) {
            $tahunAjaran->update(['status' => StatusTahunAjaran::Aktif]);

            Semester::query()->create([
                'tahun_ajaran_id' => $tahunAjaran->id,
                'jenis' => JenisSemester::Ganjil,
                'status' => StatusSemester::Berjalan,
                'tanggal_mulai' => $tahunAjaran->tanggal_mulai,
            ]);

            Semester::query()->create([
                'tahun_ajaran_id' => $tahunAjaran->id,
                'jenis' => JenisSemester::Genap,
                'status' => StatusSemester::BelumDimulai,
            ]);
        });

        $this->logAkademikService->catat(
            $tahunAjaran->cabang_sekolah_id,
            AksiLogAkademik::TahunAjaranDiaktifkan,
            $tahunAjaran->id,
            "Tahun ajaran {$tahunAjaran->nama} diaktifkan.",
        );
    }

    public function pindahKeSemesterGenap(TahunAjaran $tahunAjaran): void
    {
        if (! $tahunAjaran->isAktif()) {
            throw ValidationException::withMessages([
                'tahun_ajaran' => 'Tahun ajaran tidak aktif.',
            ]);
        }

        $tahunAjaran->load('semester');

        $ganjil = $tahunAjaran->semesterGanjil();
        $genap = $tahunAjaran->semesterGenap();

        if (! $ganjil || $ganjil->status !== StatusSemester::Berjalan) {
            throw ValidationException::withMessages([
                'semester' => 'Semester ganjil harus sedang berjalan untuk pindah ke semester genap.',
            ]);
        }

        if (! $genap || $genap->status !== StatusSemester::BelumDimulai) {
            throw ValidationException::withMessages([
                'semester' => 'Semester genap tidak siap dimulai.',
            ]);
        }

        DB::transaction(function () use ($ganjil, $genap) {
            $ganjil->update([
                'status' => StatusSemester::Selesai,
                'tanggal_selesai' => now()->toDateString(),
            ]);

            $genap->update([
                'status' => StatusSemester::Berjalan,
                'tanggal_mulai' => now()->toDateString(),
            ]);
        });

        $this->logAkademikService->catat(
            $tahunAjaran->cabang_sekolah_id,
            AksiLogAkademik::SemesterGenapDimulai,
            $tahunAjaran->id,
            "Semester genap {$tahunAjaran->nama} dimulai.",
        );
    }

    public function akhiriSemesterGenap(TahunAjaran $tahunAjaran): void
    {
        if (! $tahunAjaran->isAktif()) {
            throw ValidationException::withMessages([
                'tahun_ajaran' => 'Tahun ajaran tidak aktif.',
            ]);
        }

        $tahunAjaran->load('semester');

        $genap = $tahunAjaran->semesterGenap();

        if (! $genap || $genap->status !== StatusSemester::Berjalan) {
            throw ValidationException::withMessages([
                'semester' => 'Semester genap harus sedang berjalan.',
            ]);
        }

        $genap->update([
            'status' => StatusSemester::Selesai,
            'tanggal_selesai' => now()->toDateString(),
        ]);

        $this->logAkademikService->catat(
            $tahunAjaran->cabang_sekolah_id,
            AksiLogAkademik::SemesterGenapDiakhiri,
            $tahunAjaran->id,
            "Semester genap {$tahunAjaran->nama} diakhiri.",
        );
    }

    public function tutup(TahunAjaran $tahunAjaran): void
    {
        if (! $tahunAjaran->isAktif()) {
            throw ValidationException::withMessages([
                'tahun_ajaran' => 'Hanya tahun ajaran aktif yang dapat ditutup.',
            ]);
        }

        $tahunAjaran->load('semester');

        $genap = $tahunAjaran->semesterGenap();

        if (! $genap || $genap->status !== StatusSemester::Selesai) {
            throw ValidationException::withMessages([
                'semester' => 'Semester genap harus diselesaikan terlebih dahulu sebelum menutup tahun ajaran.',
            ]);
        }

        $tahunAjaran->update([
            'status' => StatusTahunAjaran::Ditutup,
            'tanggal_selesai' => now()->toDateString(),
        ]);

        $this->logAkademikService->catat(
            $tahunAjaran->cabang_sekolah_id,
            AksiLogAkademik::TahunAjaranDitutup,
            $tahunAjaran->id,
            "Tahun ajaran {$tahunAjaran->nama} ditutup.",
        );
    }

    public function kembalikanKeSemesterGanjil(TahunAjaran $tahunAjaran): void
    {
        if (! $tahunAjaran->isAktif()) {
            throw ValidationException::withMessages([
                'tahun_ajaran' => 'Tahun ajaran tidak aktif.',
            ]);
        }

        $tahunAjaran->load('semester');

        $ganjil = $tahunAjaran->semesterGanjil();
        $genap = $tahunAjaran->semesterGenap();

        if (! $ganjil || $ganjil->status !== StatusSemester::Selesai) {
            throw ValidationException::withMessages([
                'semester' => 'Semester ganjil harus sudah selesai.',
            ]);
        }

        if (! $genap || $genap->status !== StatusSemester::Berjalan) {
            throw ValidationException::withMessages([
                'semester' => 'Hanya dapat dikembalikan saat semester genap sedang berjalan.',
            ]);
        }

        if ($this->memilikiDataSemester($genap)) {
            throw ValidationException::withMessages([
                'semester' => 'Semester genap sudah memiliki data akademik dan tidak dapat dibatalkan.',
            ]);
        }

        DB::transaction(function () use ($ganjil, $genap) {
            $genap->update([
                'status' => StatusSemester::BelumDimulai,
                'tanggal_mulai' => null,
            ]);

            $ganjil->update([
                'status' => StatusSemester::Berjalan,
                'tanggal_selesai' => null,
            ]);
        });

        $this->logAkademikService->catat(
            $tahunAjaran->cabang_sekolah_id,
            AksiLogAkademik::DikembalikanKeGanjil,
            $tahunAjaran->id,
            "Semester genap {$tahunAjaran->nama} dibatalkan, kembali ke semester ganjil.",
        );
    }

    public function bukaKembaliSemesterGenap(TahunAjaran $tahunAjaran): void
    {
        if (! $tahunAjaran->isAktif()) {
            throw ValidationException::withMessages([
                'tahun_ajaran' => 'Tahun ajaran tidak aktif.',
            ]);
        }

        $tahunAjaran->load('semester');

        $ganjil = $tahunAjaran->semesterGanjil();
        $genap = $tahunAjaran->semesterGenap();

        if (! $ganjil || $ganjil->status !== StatusSemester::Selesai) {
            throw ValidationException::withMessages([
                'semester' => 'Semester ganjil harus sudah selesai.',
            ]);
        }

        if (! $genap || $genap->status !== StatusSemester::Selesai) {
            throw ValidationException::withMessages([
                'semester' => 'Semester genap harus sudah diakhiri.',
            ]);
        }

        if ($this->memilikiDataSemester($genap)) {
            throw ValidationException::withMessages([
                'semester' => 'Semester genap sudah memiliki data akademik dan tidak dapat dibuka kembali.',
            ]);
        }

        $genap->update([
            'status' => StatusSemester::Berjalan,
            'tanggal_selesai' => null,
        ]);

        $this->logAkademikService->catat(
            $tahunAjaran->cabang_sekolah_id,
            AksiLogAkademik::SemesterGenapDibukaKembali,
            $tahunAjaran->id,
            "Semester genap {$tahunAjaran->nama} dibuka kembali.",
        );
    }

    public function bukaKembaliTahunAjaran(TahunAjaran $tahunAjaran): void
    {
        if (! $tahunAjaran->isDitutup()) {
            throw ValidationException::withMessages([
                'tahun_ajaran' => 'Hanya tahun ajaran yang ditutup yang dapat dibuka kembali.',
            ]);
        }

        $sudahAdaAktif = TahunAjaran::query()
            ->where('cabang_sekolah_id', $tahunAjaran->cabang_sekolah_id)
            ->where('status', StatusTahunAjaran::Aktif)
            ->exists();

        if ($sudahAdaAktif) {
            throw ValidationException::withMessages([
                'tahun_ajaran' => 'Cabang ini masih memiliki tahun ajaran aktif.',
            ]);
        }

        $tahunAjaran->load('semester');

        $ganjil = $tahunAjaran->semesterGanjil();
        $genap = $tahunAjaran->semesterGenap();

        if (! $ganjil || $ganjil->status !== StatusSemester::Selesai) {
            throw ValidationException::withMessages([
                'semester' => 'Semester ganjil tidak valid untuk pembukaan kembali.',
            ]);
        }

        if (! $genap || $genap->status !== StatusSemester::Selesai) {
            throw ValidationException::withMessages([
                'semester' => 'Semester genap harus sudah selesai.',
            ]);
        }

        DB::transaction(function () use ($tahunAjaran, $genap) {
            $tahunAjaran->update([
                'status' => StatusTahunAjaran::Aktif,
                'tanggal_selesai' => null,
            ]);

            $genap->update([
                'status' => StatusSemester::Berjalan,
                'tanggal_selesai' => null,
            ]);
        });

        $this->logAkademikService->catat(
            $tahunAjaran->cabang_sekolah_id,
            AksiLogAkademik::TahunAjaranDibukaKembali,
            $tahunAjaran->id,
            "Tahun ajaran {$tahunAjaran->nama} dibuka kembali.",
        );
    }

    public function memilikiDataSemester(Semester $semester): bool
    {
        return RombelSiswa::query()
            ->where('tahun_ajaran_id', $semester->tahun_ajaran_id)
            ->exists()
            || PenugasanMengajar::query()
                ->where('tahun_ajaran_id', $semester->tahun_ajaran_id)
                ->exists()
            || PenugasanPembina::query()
                ->where('tahun_ajaran_id', $semester->tahun_ajaran_id)
                ->exists()
            || NilaiTujuanPembelajaran::query()
                ->where('semester_id', $semester->id)
                ->exists()
            || NilaiUjian::query()
                ->where('semester_id', $semester->id)
                ->exists()
            || AbsensiSiswa::query()
                ->where('semester_id', $semester->id)
                ->exists()
            || CatatanWaliKelas::query()
                ->where('semester_id', $semester->id)
                ->exists()
            || PencapaianTp::query()
                ->where('semester_id', $semester->id)
                ->exists();
    }
}
