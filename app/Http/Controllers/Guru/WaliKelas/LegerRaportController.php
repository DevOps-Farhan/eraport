<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Enums\StatusRombel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Guru\Concerns\MenanganiWaliKelas;
use App\Models\AbsensiSiswa;
use App\Models\CatatanWaliKelas;
use App\Models\Kelas;
use App\Models\PencapaianTp;
use App\Models\PenugasanMengajar;
use App\Models\Siswa;
use App\Services\KonteksAkademik;
use App\Services\NilaiRaportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Illuminate\View\View;

class LegerRaportController extends Controller
{
    use MenanganiWaliKelas;

    public function list(): View
    {
        $kelasList = $this->daftarKelasWali();

        return view('guru.wali-kelas.leger-raport.list', [
            'kelasList' => $kelasList,
        ]);
    }

    public function index(Kelas $kelas, NilaiRaportService $nilaiRaportService): View
    {
        $this->pastikanAksesKelas($kelas);

        $semester = app(KonteksAkademik::class)->tahunAjaranAktif($this->cabangSekolahId())?->semesterBerjalan;

        $siswaList = collect();
        $penugasanList = collect();
        $leger = [];

        if ($semester) {
            $siswaList = Siswa::query()
                ->whereHas('rombelSiswa', function ($query) use ($kelas) {
                    $query->where('kelas_id', $kelas->id)
                        ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                        ->where('status', StatusRombel::Aktif);
                })
                ->orderBy('nama')
                ->get();

            $penugasanList = PenugasanMengajar::query()
                ->with('mataPelajaran')
                ->where('kelas_id', $kelas->id)
                ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                ->get();

            foreach ($siswaList as $siswa) {
                $leger[$siswa->id] = [];

                foreach ($penugasanList as $penugasan) {
                    $leger[$siswa->id][$penugasan->id] = $nilaiRaportService->ringkasanMapel(
                        $penugasan,
                        $siswa,
                        $semester,
                    );
                }
            }
        }

        return view('guru.wali-kelas.leger-raport.index', [
            'kelas' => $kelas,
            'semester' => $semester,
            'siswaList' => $siswaList,
            'penugasanList' => $penugasanList,
            'leger' => $leger,
        ]);
    }

    public function cetak(Kelas $kelas, Siswa $siswa, NilaiRaportService $nilaiRaportService): Response
    {
        $kelas->load('tahunAjaran');
        $this->pastikanSiswaDiKelasWali($siswa, $kelas);

        $semester = app(KonteksAkademik::class)
            ->tahunAjaranAktif($this->cabangSekolahId())
            ?->semesterBerjalan;

        if (! $semester) {
            abort(404);
        }

        $cabang = auth()->user()->cabangSekolah;
        $waliKelas = auth()->user();

        $penugasanList = PenugasanMengajar::query()
            ->with(['mataPelajaran', 'guru'])
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
            ->get();

        $nilaiMapel = [];

        foreach ($penugasanList as $penugasan) {
            $ringkasan = $nilaiRaportService->ringkasanMapel($penugasan, $siswa, $semester);

            $pencapaian = PencapaianTp::query()
                ->where('penugasan_mengajar_id', $penugasan->id)
                ->where('siswa_id', $siswa->id)
                ->where('semester_id', $semester->id)
                ->value('pencapaian');

            $nilaiMapel[] = [
                'mapel' => $penugasan->mataPelajaran,
                'guru' => $penugasan->guru,
                'ringkasan' => $ringkasan,
                'pencapaian' => $pencapaian,
            ];
        }

        $absensi = AbsensiSiswa::query()
            ->where('siswa_id', $siswa->id)
            ->where('semester_id', $semester->id)
            ->first();

        $catatan = CatatanWaliKelas::query()
            ->where('siswa_id', $siswa->id)
            ->where('semester_id', $semester->id)
            ->value('catatan');

        $kokurikuler = \App\Models\Kokurikuler::query()
            ->where('siswa_id', $siswa->id)
            ->where('semester_id', $semester->id)
            ->value('capaian_kokurikuler');

        $nilaiEkstrakurikuler = \App\Models\NilaiEkstrakurikuler::query()
            ->with('ekstrakurikuler')
            ->where('siswa_id', $siswa->id)
            ->where('semester_id', $semester->id)
            ->get();

        $pdf = Pdf::loadView('guru.wali-kelas.leger-raport.pdf', [
            'siswa' => $siswa,
            'kelas' => $kelas,
            'semester' => $semester,
            'cabang' => $cabang,
            'waliKelas' => $waliKelas,
            'nilaiMapel' => $nilaiMapel,
            'absensi' => $absensi,
            'catatan' => $catatan,
            'kokurikuler' => $kokurikuler,
            'nilaiEkstrakurikuler' => $nilaiEkstrakurikuler,
        ]);

        $filename = 'Raport - ' . $siswa->nama . '.pdf';

        if (request('action') === 'preview') {
            return $pdf->stream($filename);
        }

        return $pdf->download($filename);
    }
}
