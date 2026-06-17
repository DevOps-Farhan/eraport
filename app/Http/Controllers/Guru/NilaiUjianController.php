<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Guru\Concerns\MenanganiPenugasanGuru;
use App\Http\Requests\Guru\StoreNilaiUjianRequest;
use App\Models\NilaiUjian;
use App\Models\PenugasanMengajar;
use App\Services\NilaiRaportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NilaiUjianController extends Controller
{
    use MenanganiPenugasanGuru;

    public function index(): View
    {
        return view('guru.kelola-nilai.nilai-ujian.index', [
            'penugasanList' => $this->penugasanGuru(),
        ]);
    }

    public function show(PenugasanMengajar $penugasan, NilaiRaportService $nilaiRaportService): View
    {
        $penugasan = $this->pastikanPenugasanGuru($penugasan);
        $semester = $this->semesterBerjalan();
        $siswaList = $nilaiRaportService->siswaAktifKelas($penugasan);

        $nilaiExisting = collect();

        if ($semester) {
            $nilaiExisting = NilaiUjian::query()
                ->where('penugasan_mengajar_id', $penugasan->id)
                ->where('semester_id', $semester->id)
                ->get()
                ->keyBy('siswa_id');
        }

        $ringkasan = [];

        if ($semester) {
            foreach ($siswaList as $siswa) {
                $ringkasan[$siswa->id] = $nilaiRaportService->ringkasanMapel($penugasan, $siswa, $semester);
            }
        }

        return view('guru.kelola-nilai.nilai-ujian.show', [
            'penugasan' => $penugasan,
            'siswaList' => $siswaList,
            'nilaiExisting' => $nilaiExisting,
            'ringkasan' => $ringkasan,
            'semester' => $semester,
            'dapatDiedit' => $this->semesterDapatDiedit(),
        ]);
    }

    public function store(StoreNilaiUjianRequest $request, PenugasanMengajar $penugasan): RedirectResponse
    {
        if (! $this->semesterDapatDiedit()) {
            return back()->with('gagal', 'Semester tidak sedang berjalan. Data tidak dapat diubah.');
        }

        $penugasan = $this->pastikanPenugasanGuru($penugasan);
        $semester = $this->semesterBerjalan();

        if (! $semester) {
            return back()->with('gagal', 'Tidak ada semester berjalan.');
        }

        foreach ($request->input('nilai', []) as $siswaId => $nilai) {
            if ($nilai === null || $nilai === '') {
                continue;
            }

            NilaiUjian::query()->updateOrCreate(
                [
                    'penugasan_mengajar_id' => $penugasan->id,
                    'siswa_id' => (int) $siswaId,
                    'semester_id' => $semester->id,
                ],
                [
                    'nilai' => (float) $nilai,
                ],
            );
        }

        return redirect()
            ->route('guru.kelola-nilai.nilai-ujian.show', $penugasan)
            ->with('sukses', 'Nilai ujian berhasil disimpan.');
    }
}
