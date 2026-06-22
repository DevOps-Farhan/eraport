<?php

namespace App\Http\Controllers\Pembina;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Pembina\Concerns\MenanganiPembina;
use App\Models\AnggotaEkstrakurikuler;
use App\Models\Ekstrakurikuler;
use App\Models\NilaiEkstrakurikuler;
use App\Services\KonteksAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PenilaianController extends Controller
{
    use MenanganiPembina;

    public function index(): View
    {
        $ekstrakurikulerList = $this->daftarEkstrakurikulerPembina();

        return view('pembina.penilaian.index', [
            'ekstrakurikulerList' => $ekstrakurikulerList,
        ]);
    }

    public function show(Ekstrakurikuler $ekstrakurikuler): View
    {
        $this->pastikanAksesEkstrakurikuler($ekstrakurikuler);
        
        $konteks = app(KonteksAkademik::class);
        $tahunAjaranAktif = $konteks->tahunAjaranAktif(Auth::user()->cabang_sekolah_id);
        $semesterAktif = $tahunAjaranAktif?->semesterBerjalan;

        $anggotaList = AnggotaEkstrakurikuler::query()
            ->with(['siswa.rombelSiswa.kelas'])
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->get()
            ->sortBy('siswa.nama');

        $nilaiList = collect();
        if ($semesterAktif) {
            $nilaiList = NilaiEkstrakurikuler::query()
                ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
                ->where('semester_id', $semesterAktif->id)
                ->whereIn('siswa_id', $anggotaList->pluck('siswa_id'))
                ->get()
                ->keyBy('siswa_id');
        }

        return view('pembina.penilaian.show', [
            'ekstrakurikuler' => $ekstrakurikuler,
            'anggotaList' => $anggotaList,
            'nilaiList' => $nilaiList,
            'tahunAjaran' => $tahunAjaranAktif,
            'semesterAktif' => $semesterAktif,
        ]);
    }

    public function store(Request $request, Ekstrakurikuler $ekstrakurikuler): RedirectResponse
    {
        $this->pastikanAksesEkstrakurikuler($ekstrakurikuler);
        
        $konteks = app(KonteksAkademik::class);
        $tahunAjaranAktif = $konteks->tahunAjaranAktif(Auth::user()->cabang_sekolah_id);
        $semesterAktif = $tahunAjaranAktif?->semesterBerjalan;

        if (! $semesterAktif) {
            return back()->with('gagal', 'Tidak ada semester aktif. Tidak dapat menyimpan nilai.');
        }

        $validated = $request->validate([
            'nilai' => ['required', 'array'],
            'nilai.*.siswa_id' => ['required', 'integer', 'exists:siswa,id'],
            'nilai.*.nilai' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'nilai.*.predikat' => ['nullable', 'string', 'max:2'],
            'nilai.*.deskripsi' => ['nullable', 'string'],
        ]);

        foreach ($validated['nilai'] as $data) {
            NilaiEkstrakurikuler::updateOrCreate(
                [
                    'ekstrakurikuler_id' => $ekstrakurikuler->id,
                    'siswa_id' => $data['siswa_id'],
                    'semester_id' => $semesterAktif->id,
                ],
                [
                    'nilai' => $data['nilai'],
                    'predikat' => $data['predikat'],
                    'deskripsi' => $data['deskripsi'],
                ]
            );
        }

        return redirect()
            ->route('pembina.penilaian.show', $ekstrakurikuler)
            ->with('sukses', 'Penilaian berhasil disimpan.');
    }
}
