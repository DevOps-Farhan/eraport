<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Guru\Concerns\MenanganiWaliKelas;
use App\Models\Kelas;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Enums\StatusRombel;
use App\Enums\StatusSemester;
use App\Models\Kokurikuler;
use App\Models\Siswa;
use App\Services\KonteksAkademik;
use Illuminate\Http\RedirectResponse;

class KokurikulerController extends Controller
{
    use MenanganiWaliKelas;

    public function list(): View
    {
        $kelasList = $this->daftarKelasWali();

        return view('guru.wali-kelas.kokurikuler.list', [
            'kelasList' => $kelasList,
        ]);
    }

    public function index(Kelas $kelas): View
    {
        $this->pastikanAksesKelas($kelas);

        $semester = app(KonteksAkademik::class)->tahunAjaranAktif($this->cabangSekolahId())?->semesterBerjalan;

        $siswaList = collect();
        $kokurikuler = collect();

        if ($semester) {
            $siswaList = Siswa::query()
                ->whereHas('rombelSiswa', function ($query) use ($kelas) {
                    $query->where('kelas_id', $kelas->id)
                        ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                        ->where('status', StatusRombel::Aktif);
                })
                ->orderBy('nama')
                ->get();

            $kokurikuler = Kokurikuler::query()
                ->where('semester_id', $semester->id)
                ->whereIn('siswa_id', $siswaList->pluck('id'))
                ->get()
                ->keyBy('siswa_id');
        }

        return view('guru.wali-kelas.kokurikuler.index', [
            'kelas' => $kelas,
            'semester' => $semester,
            'siswaList' => $siswaList,
            'kokurikuler' => $kokurikuler,
            'dapatDiedit' => $semester && $semester->status === StatusSemester::Berjalan,
        ]);
    }

    public function store(Request $request, Kelas $kelas): RedirectResponse
    {
        $this->pastikanAksesKelas($kelas);
        
        $semester = app(KonteksAkademik::class)
            ->tahunAjaranAktif($this->cabangSekolahId())
            ?->semesterBerjalan;

        if (! $semester || $semester->status !== StatusSemester::Berjalan) {
            return back()->with('gagal', 'Semester tidak sedang berjalan.');
        }

        $request->validate([
            'capaian' => ['nullable', 'array'],
            'capaian.*' => ['nullable', 'string'],
        ]);

        foreach ($request->input('capaian', []) as $siswaId => $teks) {
            $siswa = Siswa::query()->find((int) $siswaId);

            if (! $siswa) {
                continue;
            }

            $this->pastikanSiswaDiKelasWali($siswa, $kelas);

            Kokurikuler::query()->updateOrCreate(
                ['siswa_id' => $siswa->id, 'semester_id' => $semester->id],
                ['capaian_kokurikuler' => $teks ?: null],
            );
        }

        return redirect()
            ->route('guru.wali-kelas.kokurikuler.show', $kelas)
            ->with('sukses', 'Data kokurikuler berhasil disimpan.');
    }
}
