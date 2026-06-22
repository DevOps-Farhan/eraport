<?php

namespace App\Http\Controllers\Pembina;

use App\Enums\StatusRombel;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pembina\Concerns\MenanganiPembina;
use App\Models\AnggotaEkstrakurikuler;
use App\Models\Ekstrakurikuler;
use App\Models\Siswa;
use App\Services\KonteksAkademik;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class EkstrakurikulerController extends Controller
{
    use MenanganiPembina;

    public function index(): View
    {
        $ekstrakurikulerList = $this->daftarEkstrakurikulerPembina();

        return view('pembina.ekstrakurikuler.index', [
            'ekstrakurikulerList' => $ekstrakurikulerList,
        ]);
    }

    public function show(Ekstrakurikuler $ekstrakurikuler): View
    {
        $this->pastikanAksesEkstrakurikuler($ekstrakurikuler);
        
        $tahunAjaranAktif = app(KonteksAkademik::class)->tahunAjaranAktif(Auth::user()->cabang_sekolah_id);

        $anggotaList = AnggotaEkstrakurikuler::query()
            ->with('siswa.rombelSiswa.kelas')
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->get()
            ->sortBy('siswa.nama');

        $calonAnggotaList = Siswa::query()
            ->where('cabang_sekolah_id', Auth::user()->cabang_sekolah_id)
            ->where('aktif', true)
            ->whereDoesntHave('anggotaEkstrakurikuler', function ($query) use ($ekstrakurikuler, $tahunAjaranAktif) {
                $query->where('ekstrakurikuler_id', $ekstrakurikuler->id)
                    ->where('tahun_ajaran_id', $tahunAjaranAktif->id);
            })
            ->with(['rombelSiswa' => function($query) use ($tahunAjaranAktif) {
                $query->where('tahun_ajaran_id', $tahunAjaranAktif->id)->where('status', StatusRombel::Aktif);
            }, 'rombelSiswa.kelas'])
            ->orderBy('nama')
            ->get();

        return view('pembina.ekstrakurikuler.show', [
            'ekstrakurikuler' => $ekstrakurikuler,
            'anggotaList' => $anggotaList,
            'calonAnggotaList' => $calonAnggotaList,
            'tahunAjaran' => $tahunAjaranAktif,
        ]);
    }

    public function storeAnggota(Request $request, Ekstrakurikuler $ekstrakurikuler): RedirectResponse
    {
        $this->pastikanAksesEkstrakurikuler($ekstrakurikuler);
        
        $tahunAjaranAktif = app(KonteksAkademik::class)->tahunAjaranAktif(Auth::user()->cabang_sekolah_id);

        $validated = $request->validate([
            'siswa_ids' => ['required', 'array'],
            'siswa_ids.*' => ['integer', 'exists:siswa,id'],
        ]);

        foreach ($validated['siswa_ids'] as $siswaId) {
            AnggotaEkstrakurikuler::firstOrCreate([
                'ekstrakurikuler_id' => $ekstrakurikuler->id,
                'tahun_ajaran_id' => $tahunAjaranAktif->id,
                'siswa_id' => $siswaId,
            ]);
        }

        return redirect()
            ->route('pembina.ekstrakurikuler.show', $ekstrakurikuler)
            ->with('sukses', 'Anggota berhasil ditambahkan.');
    }

    public function destroyAnggota(Ekstrakurikuler $ekstrakurikuler, Siswa $siswa): RedirectResponse
    {
        $this->pastikanAksesEkstrakurikuler($ekstrakurikuler);
        
        $tahunAjaranAktif = app(KonteksAkademik::class)->tahunAjaranAktif(Auth::user()->cabang_sekolah_id);

        AnggotaEkstrakurikuler::query()
            ->where('ekstrakurikuler_id', $ekstrakurikuler->id)
            ->where('tahun_ajaran_id', $tahunAjaranAktif->id)
            ->where('siswa_id', $siswa->id)
            ->delete();

        return redirect()
            ->route('pembina.ekstrakurikuler.show', $ekstrakurikuler)
            ->with('sukses', 'Anggota berhasil dihapus.');
    }
}
