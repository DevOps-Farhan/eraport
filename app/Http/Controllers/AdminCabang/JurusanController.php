<?php

namespace App\Http\Controllers\AdminCabang;

use App\Http\Controllers\AdminCabang\Concerns\MenanganiScopeCabang;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreJurusanRequest;
use App\Http\Requests\AdminCabang\UpdateJurusanRequest;
use App\Models\Jurusan;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class JurusanController extends Controller
{
    use MenanganiScopeCabang;

    public function index(): View
    {
        $jurusan = Jurusan::query()
            ->where('cabang_sekolah_id', $this->cabangSekolahId())
            ->orderBy('nama')
            ->paginate(10);

        return view('admin-cabang.data-akademik.jurusan.index', [
            'jurusan' => $jurusan,
        ]);
    }

    public function store(StoreJurusanRequest $request): RedirectResponse
    {
        Jurusan::query()->create([
            'cabang_sekolah_id' => $this->cabangSekolahId(),
            'kode' => $request->string('kode')->value(),
            'nama' => $request->string('nama')->value(),
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()
            ->route('admin-cabang.data-akademik.jurusan.index')
            ->with('sukses', 'Jurusan berhasil ditambahkan.');
    }

    public function update(UpdateJurusanRequest $request, Jurusan $jurusan): RedirectResponse
    {
        $this->pastikanMilikCabang($jurusan);

        $jurusan->update([
            'kode' => $request->string('kode')->value(),
            'nama' => $request->string('nama')->value(),
            'aktif' => $request->boolean('aktif', false),
        ]);

        return redirect()
            ->route('admin-cabang.data-akademik.jurusan.index')
            ->with('sukses', 'Jurusan berhasil diperbarui.');
    }

    public function destroy(Jurusan $jurusan): RedirectResponse
    {
        $this->pastikanMilikCabang($jurusan);

        if ($jurusan->kelas()->exists()) {
            return redirect()
                ->route('admin-cabang.data-akademik.jurusan.index')
                ->with('gagal', 'Jurusan tidak dapat dihapus karena masih memiliki kelas.');
        }

        $jurusan->delete();

        return redirect()
            ->route('admin-cabang.data-akademik.jurusan.index')
            ->with('sukses', 'Jurusan berhasil dihapus.');
    }
}
