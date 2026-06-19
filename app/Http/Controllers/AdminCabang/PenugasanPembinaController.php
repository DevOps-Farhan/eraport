<?php

namespace App\Http\Controllers\AdminCabang;

use App\Enums\PeranPengguna;
use App\Http\Controllers\AdminCabang\Concerns\MenanganiScopeCabang;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StorePenugasanPembinaRequest;
use App\Models\Ekstrakurikuler;
use App\Models\PenugasanPembina;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PenugasanPembinaController extends Controller
{
    use MenanganiScopeCabang;

    public function index(): View
    {
        $cabangId = $this->cabangSekolahId();
        $tahunAjaran = app(\App\Services\KonteksAkademik::class)->tahunAjaranAktif($cabangId);

        $penugasan = PenugasanPembina::query()->whereRaw('1 = 0')->paginate(10);
        $pembinaList = User::query()
            ->where('cabang_sekolah_id', $cabangId)
            ->where('peran', PeranPengguna::PembinaEkstrakurikuler)
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();
        $ekstraAktifList = Ekstrakurikuler::query()
            ->where('cabang_sekolah_id', $cabangId)
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        if ($tahunAjaran) {
            $penugasan = PenugasanPembina::query()
                ->with(['ekstrakurikuler', 'pembina'])
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->orderBy('id')
                ->paginate(10);
        }

        return view('admin-cabang.data-akademik.penugasan-pembina.index', [
            'penugasan' => $penugasan,
            'tahunAjaran' => $tahunAjaran,
            'pembinaList' => $pembinaList,
            'ekstraAktifList' => $ekstraAktifList,
        ]);
    }

    public function store(StorePenugasanPembinaRequest $request): RedirectResponse
    {
        $tahunAjaran = $this->tahunAjaranAktif();

        try {
            PenugasanPembina::query()->create($request->validated());
        } catch (\Illuminate\Database\QueryException) {
            return redirect()
                ->route('admin-cabang.data-akademik.penugasan-pembina.index')
                ->with('gagal', 'Ekstrakurikuler ini sudah memiliki pembina pada tahun ajaran aktif.');
        }

        return redirect()
            ->route('admin-cabang.data-akademik.penugasan-pembina.index')
            ->with('sukses', 'Penugasan pembina berhasil ditambahkan.');
    }

    public function destroy(PenugasanPembina $penugasanPembina): RedirectResponse
    {
        $penugasanPembina->loadMissing('tahunAjaran');

        if ($penugasanPembina->tahunAjaran->cabang_sekolah_id !== $this->cabangSekolahId()) {
            abort(404);
        }

        $this->pastikanTahunAjaranAktif($penugasanPembina->tahunAjaran);

        $penugasanPembina->delete();

        return redirect()
            ->route('admin-cabang.data-akademik.penugasan-pembina.index')
            ->with('sukses', 'Penugasan pembina berhasil dihapus.');
    }
}
