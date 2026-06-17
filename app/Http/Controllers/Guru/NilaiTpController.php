<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Guru\Concerns\MenanganiPenugasanGuru;
use App\Http\Requests\Guru\StoreNilaiTpRequest;
use App\Models\NilaiTujuanPembelajaran;
use App\Models\PenugasanMengajar;
use App\Models\TujuanPembelajaran;
use App\Services\DeskripsiPencapaianService;
use App\Services\NilaiRaportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NilaiTpController extends Controller
{
    use MenanganiPenugasanGuru;

    public function index(): View
    {
        return view('guru.kelola-nilai.input-nilai-tp.index', [
            'penugasanList' => $this->penugasanGuru(),
        ]);
    }

    public function show(PenugasanMengajar $penugasan, NilaiRaportService $nilaiRaportService): View
    {
        $penugasan = $this->pastikanPenugasanGuru($penugasan);
        $semester = $this->semesterBerjalan();

        $tujuanPembelajaran = TujuanPembelajaran::query()
            ->where('penugasan_mengajar_id', $penugasan->id)
            ->orderBy('urutan')
            ->orderBy('kode')
            ->get();

        $siswaList = $nilaiRaportService->siswaAktifKelas($penugasan);

        $nilaiExisting = collect();

        if ($semester && $tujuanPembelajaran->isNotEmpty()) {
            $nilaiExisting = NilaiTujuanPembelajaran::query()
                ->whereIn('tujuan_pembelajaran_id', $tujuanPembelajaran->pluck('id'))
                ->where('semester_id', $semester->id)
                ->get()
                ->keyBy(fn ($item) => $item->siswa_id.'_'.$item->tujuan_pembelajaran_id);
        }

        return view('guru.kelola-nilai.input-nilai-tp.show', [
            'penugasan' => $penugasan,
            'tujuanPembelajaran' => $tujuanPembelajaran,
            'siswaList' => $siswaList,
            'nilaiExisting' => $nilaiExisting,
            'semester' => $semester,
            'dapatDiedit' => $this->semesterDapatDiedit(),
        ]);
    }

    public function store(
        StoreNilaiTpRequest $request,
        PenugasanMengajar $penugasan,
        DeskripsiPencapaianService $deskripsiService,
    ): RedirectResponse {
        if (! $this->semesterDapatDiedit()) {
            return back()->with('gagal', 'Semester tidak sedang berjalan. Data tidak dapat diubah.');
        }

        $penugasan = $this->pastikanPenugasanGuru($penugasan);
        $semester = $this->semesterBerjalan();

        if (! $semester) {
            return back()->with('gagal', 'Tidak ada semester berjalan.');
        }

        $tujuanPembelajaran = TujuanPembelajaran::query()
            ->where('penugasan_mengajar_id', $penugasan->id)
            ->get()
            ->keyBy('id');

        foreach ($request->input('nilai', []) as $key => $nilai) {
            if ($nilai === null || $nilai === '') {
                continue;
            }

            [$siswaId, $tpId] = array_map('intval', explode('_', (string) $key));

            $tp = $tujuanPembelajaran->get($tpId);

            if (! $tp) {
                continue;
            }

            $nilaiFloat = (float) $nilai;
            $hasil = $deskripsiService->generate($tp->deskripsi_kompetensi, $nilaiFloat);

            NilaiTujuanPembelajaran::query()->updateOrCreate(
                [
                    'tujuan_pembelajaran_id' => $tp->id,
                    'siswa_id' => $siswaId,
                    'semester_id' => $semester->id,
                ],
                [
                    'nilai' => $nilaiFloat,
                    'predikat' => $hasil['predikat'],
                    'deskripsi_pencapaian' => $hasil['deskripsi_pencapaian'],
                ],
            );
        }

        return redirect()
            ->route('guru.kelola-nilai.input-nilai-tp.show', $penugasan)
            ->with('sukses', 'Nilai tujuan pembelajaran berhasil disimpan.');
    }
}
