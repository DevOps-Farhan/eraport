<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Enums\StatusRombel;
use App\Enums\StatusSemester;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Guru\Concerns\MenanganiWaliKelas;
use App\Http\Requests\Guru\StoreAbsensiCatatanRequest;
use App\Models\AbsensiSiswa;
use App\Models\CatatanWaliKelas;
use App\Models\Siswa;
use App\Services\KonteksAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class AbsensiCatatanController extends Controller
{
    use MenanganiWaliKelas;

    public function index(): View
    {
        $kelas = $this->kelasWali();
        $semester = $kelas
            ? app(KonteksAkademik::class)->tahunAjaranAktif($this->cabangSekolahId())?->semesterBerjalan
            : null;

        $siswaList = collect();
        $absensi = collect();
        $catatan = collect();

        if ($kelas && $semester) {
            $siswaList = Siswa::query()
                ->whereHas('rombelSiswa', function ($query) use ($kelas) {
                    $query->where('kelas_id', $kelas->id)
                        ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                        ->where('status', StatusRombel::Aktif);
                })
                ->orderBy('nama')
                ->get();

            $absensi = AbsensiSiswa::query()
                ->where('semester_id', $semester->id)
                ->whereIn('siswa_id', $siswaList->pluck('id'))
                ->get()
                ->keyBy('siswa_id');

            $catatan = CatatanWaliKelas::query()
                ->where('semester_id', $semester->id)
                ->whereIn('siswa_id', $siswaList->pluck('id'))
                ->get()
                ->keyBy('siswa_id');
        }

        return view('guru.wali-kelas.absensi-catatan.index', [
            'kelas' => $kelas,
            'semester' => $semester,
            'siswaList' => $siswaList,
            'absensi' => $absensi,
            'catatan' => $catatan,
            'dapatDiedit' => $semester && $semester->status === StatusSemester::Berjalan,
        ]);
    }

    public function store(StoreAbsensiCatatanRequest $request): RedirectResponse
    {
        $kelas = $this->pastikanKelasWali();
        $semester = app(KonteksAkademik::class)
            ->tahunAjaranAktif($this->cabangSekolahId())
            ?->semesterBerjalan;

        if (! $semester || $semester->status !== StatusSemester::Berjalan) {
            return back()->with('gagal', 'Semester tidak sedang berjalan.');
        }

        foreach ($request->input('absensi', []) as $siswaId => $data) {
            $siswa = Siswa::query()->find((int) $siswaId);

            if (! $siswa) {
                continue;
            }

            $this->pastikanSiswaDiKelasWali($siswa);

            AbsensiSiswa::query()->updateOrCreate(
                ['siswa_id' => $siswa->id, 'semester_id' => $semester->id],
                [
                    'sakit' => (int) ($data['sakit'] ?? 0),
                    'izin' => (int) ($data['izin'] ?? 0),
                    'alpha' => (int) ($data['alpha'] ?? 0),
                ],
            );
        }

        foreach ($request->input('catatan', []) as $siswaId => $teks) {
            $siswa = Siswa::query()->find((int) $siswaId);

            if (! $siswa) {
                continue;
            }

            $this->pastikanSiswaDiKelasWali($siswa);

            CatatanWaliKelas::query()->updateOrCreate(
                ['siswa_id' => $siswa->id, 'semester_id' => $semester->id],
                ['catatan' => $teks ?: null],
            );
        }

        return redirect()
            ->route('guru.wali-kelas.absensi-catatan')
            ->with('sukses', 'Absensi dan catatan berhasil disimpan.');
    }
}
