<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPusat\StoreCabangSekolahRequest;
use App\Http\Requests\AdminPusat\UpdateCabangSekolahRequest;
use App\Models\CabangSekolah;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CabangSekolahController extends Controller
{
    public function index(): View
    {
        $cabangSekolah = CabangSekolah::query()
            ->withCount('pengguna')
            ->orderBy('nama')
            ->paginate(10);

        return view('admin-pusat.cabang-sekolah.index', [
            'cabangSekolah' => $cabangSekolah,
        ]);
    }

    public function store(StoreCabangSekolahRequest $request): RedirectResponse
    {
        CabangSekolah::query()->create([
            ...$request->validated(),
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()
            ->route('admin-pusat.cabang-sekolah.index')
            ->with('sukses', 'Cabang sekolah berhasil ditambahkan.');
    }

    public function update(UpdateCabangSekolahRequest $request, CabangSekolah $cabangSekolah): RedirectResponse
    {
        $cabangSekolah->update([
            ...$request->validated(),
            'aktif' => $request->boolean('aktif', false),
        ]);

        return redirect()
            ->route('admin-pusat.cabang-sekolah.index')
            ->with('sukses', 'Cabang sekolah berhasil diperbarui.');
    }

    public function destroy(CabangSekolah $cabangSekolah): RedirectResponse
    {
        if ($cabangSekolah->pengguna()->exists()) {
            return redirect()
                ->route('admin-pusat.cabang-sekolah.index')
                ->with('gagal', 'Cabang tidak dapat dihapus karena masih memiliki pengguna.');
        }

        $cabangSekolah->delete();

        return redirect()
            ->route('admin-pusat.cabang-sekolah.index')
            ->with('sukses', 'Cabang sekolah berhasil dihapus.');
    }
}
