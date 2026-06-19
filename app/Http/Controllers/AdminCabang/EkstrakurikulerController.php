<?php

namespace App\Http\Controllers\AdminCabang;

use App\Enums\PeranPengguna;
use App\Http\Controllers\AdminCabang\Concerns\MenanganiScopeCabang;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreEkstrakurikulerRequest;
use App\Http\Requests\AdminCabang\UpdateEkstrakurikulerRequest;
use App\Models\Ekstrakurikuler;
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

        return view('admin-cabang.data-akademik.ekstrakurikuler.index', [
            'ekstrakurikuler' => $ekstrakurikuler,
            'tahunAjaran' => $tahunAjaran,
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
}
