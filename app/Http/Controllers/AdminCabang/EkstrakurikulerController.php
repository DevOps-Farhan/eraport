<?php

namespace App\Http\Controllers\AdminCabang;

use App\Enums\PeranPengguna;
use App\Http\Controllers\AdminCabang\Concerns\MenanganiScopeCabang;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreEkstrakurikulerRequest;
use App\Http\Requests\AdminCabang\StorePenugasanPembinaRequest;
use App\Http\Requests\AdminCabang\UpdateEkstrakurikulerRequest;
use App\Models\Ekstrakurikuler;
use App\Models\PenugasanPembina;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class EkstrakurikulerController extends Controller
{
    use MenanganiScopeCabang;

    public function index(): View
    {
        $cabangId = $this->cabangSekolahId();
        $tahunAjaran = app(\App\Services\KonteksAkademik::class)->tahunAjaranAktif($cabangId);

        $ekstrakurikuler = Ekstrakurikuler::query()
            ->where('cabang_sekolah_id', $cabangId)
            ->orderBy('nama')
            ->paginate(10, ['*'], 'ekstra_page');

        $penugasan = PenugasanPembina::query()->whereRaw('1 = 0')->paginate(10, ['*'], 'pembina_page');
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
                ->paginate(10, ['*'], 'pembina_page');
        }

        return view('admin-cabang.data-akademik.ekstrakurikuler.index', [
            'ekstrakurikuler' => $ekstrakurikuler,
            'penugasan' => $penugasan,
            'tahunAjaran' => $tahunAjaran,
            'pembinaList' => $pembinaList,
            'ekstraAktifList' => $ekstraAktifList,
        ]);
    }

    public function store(StoreEkstrakurikulerRequest $request): RedirectResponse
    {
        Ekstrakurikuler::query()->create([
            'cabang_sekolah_id' => $this->cabangSekolahId(),
            'kode' => $request->string('kode')->value(),
            'nama' => $request->string('nama')->value(),
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()
            ->route('admin-cabang.data-akademik.ekstrakurikuler.index')
            ->with('sukses', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function update(UpdateEkstrakurikulerRequest $request, Ekstrakurikuler $ekstrakurikuler): RedirectResponse
    {
        $this->pastikanMilikCabang($ekstrakurikuler);

        $ekstrakurikuler->update([
            'kode' => $request->string('kode')->value(),
            'nama' => $request->string('nama')->value(),
            'aktif' => $request->boolean('aktif', false),
        ]);

        return redirect()
            ->route('admin-cabang.data-akademik.ekstrakurikuler.index')
            ->with('sukses', 'Ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Ekstrakurikuler $ekstrakurikuler): RedirectResponse
    {
        $this->pastikanMilikCabang($ekstrakurikuler);

        if ($ekstrakurikuler->penugasanPembina()->exists()) {
            return redirect()
                ->route('admin-cabang.data-akademik.ekstrakurikuler.index')
                ->with('gagal', 'Ekstrakurikuler tidak dapat dihapus karena masih memiliki penugasan pembina.');
        }

        $ekstrakurikuler->delete();

        return redirect()
            ->route('admin-cabang.data-akademik.ekstrakurikuler.index')
            ->with('sukses', 'Ekstrakurikuler berhasil dihapus.');
    }

    public function storePenugasan(StorePenugasanPembinaRequest $request): RedirectResponse
    {
        $tahunAjaran = $this->tahunAjaranAktif();

        try {
            PenugasanPembina::query()->create($request->validated());
        } catch (\Illuminate\Database\QueryException) {
            return redirect()
                ->route('admin-cabang.data-akademik.ekstrakurikuler.index')
                ->with('gagal', 'Ekstrakurikuler ini sudah memiliki pembina pada tahun ajaran aktif.');
        }

        return redirect()
            ->route('admin-cabang.data-akademik.ekstrakurikuler.index')
            ->with('sukses', 'Penugasan pembina berhasil ditambahkan.');
    }

    public function destroyPenugasan(PenugasanPembina $penugasanPembina): RedirectResponse
    {
        $penugasanPembina->loadMissing('tahunAjaran');

        if ($penugasanPembina->tahunAjaran->cabang_sekolah_id !== $this->cabangSekolahId()) {
            abort(404);
        }

        $this->pastikanTahunAjaranAktif($penugasanPembina->tahunAjaran);

        $penugasanPembina->delete();

        return redirect()
            ->route('admin-cabang.data-akademik.ekstrakurikuler.index')
            ->with('sukses', 'Penugasan pembina berhasil dihapus.');
    }
}
