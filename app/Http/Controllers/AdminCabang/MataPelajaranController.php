<?php

namespace App\Http\Controllers\AdminCabang;

use App\Enums\KelompokMataPelajaran;
use App\Http\Controllers\AdminCabang\Concerns\MenanganiScopeCabang;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreMataPelajaranRequest;
use App\Http\Requests\AdminCabang\UpdateMataPelajaranRequest;
use App\Models\MataPelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MataPelajaranController extends Controller
{
    use MenanganiScopeCabang;

    public function index(): View
    {
        $mataPelajaran = MataPelajaran::query()
            ->where('cabang_sekolah_id', $this->cabangSekolahId())
            ->orderBy('nama')
            ->paginate(10);

        return view('admin-cabang.data-akademik.mata-pelajaran.index', [
            'mataPelajaran' => $mataPelajaran,
        ]);
    }

    public function store(StoreMataPelajaranRequest $request): RedirectResponse
    {
        MataPelajaran::query()->create([
            'cabang_sekolah_id' => $this->cabangSekolahId(),
            'kode' => $request->string('kode')->value(),
            'nama' => $request->string('nama')->value(),
            'kelompok' => $request->enum('kelompok', KelompokMataPelajaran::class),
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()
            ->route('admin-cabang.data-akademik.mata-pelajaran.index')
            ->with('sukses', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function update(UpdateMataPelajaranRequest $request, MataPelajaran $mataPelajaran): RedirectResponse
    {
        $this->pastikanMilikCabang($mataPelajaran);

        $mataPelajaran->update([
            'kode' => $request->string('kode')->value(),
            'nama' => $request->string('nama')->value(),
            'kelompok' => $request->enum('kelompok', KelompokMataPelajaran::class),
            'aktif' => $request->boolean('aktif', false),
        ]);

        return redirect()
            ->route('admin-cabang.data-akademik.mata-pelajaran.index')
            ->with('sukses', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy(MataPelajaran $mataPelajaran): RedirectResponse
    {
        $this->pastikanMilikCabang($mataPelajaran);

        if ($mataPelajaran->penugasanMengajar()->exists()) {
            return redirect()
                ->route('admin-cabang.data-akademik.mata-pelajaran.index')
                ->with('gagal', 'Mata pelajaran tidak dapat dihapus karena masih digunakan pada penugasan mengajar.');
        }

        $mataPelajaran->delete();

        return redirect()
            ->route('admin-cabang.data-akademik.mata-pelajaran.index')
            ->with('sukses', 'Mata pelajaran berhasil dihapus.');
    }
}
