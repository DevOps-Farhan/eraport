<?php

namespace App\Http\Controllers\AdminCabang;

use App\Enums\PeranPengguna;
use App\Http\Controllers\AdminCabang\Concerns\MenanganiScopeCabang;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreKelasRequest;
use App\Http\Requests\AdminCabang\UpdateKelasRequest;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KelasController extends Controller
{
    use MenanganiScopeCabang;

    public function index(): View
    {
        $cabangId = $this->cabangSekolahId();
        $tahunAjaran = app(\App\Services\KonteksAkademik::class)->tahunAjaranAktif($cabangId);

        $kelas = Kelas::query()->whereRaw('1 = 0')->paginate(10);
        $jurusanList = Jurusan::query()
            ->where('cabang_sekolah_id', $cabangId)
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();
        $guruList = User::query()
            ->where('cabang_sekolah_id', $cabangId)
            ->where('peran', PeranPengguna::Guru)
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();

        if ($tahunAjaran) {
            $kelas = Kelas::query()
                ->with(['jurusan', 'waliKelas'])
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->orderBy('tingkat')
                ->orderBy('nama')
                ->paginate(10);
        }

        return view('admin-cabang.data-akademik.kelas.index', [
            'kelas' => $kelas,
            'tahunAjaran' => $tahunAjaran,
            'jurusanList' => $jurusanList,
            'guruList' => $guruList,
        ]);
    }

    public function store(StoreKelasRequest $request): RedirectResponse
    {
        $tahunAjaran = $this->tahunAjaranAktif();
        $validated = $request->validated();

        Kelas::query()->create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jurusan_id' => $validated['jurusan_id'],
            'tingkat' => $validated['tingkat'],
            'nama' => $validated['nama'],
            'wali_kelas_id' => $validated['wali_kelas_id'] ?? null,
        ]);

        return redirect()
            ->route('admin-cabang.data-akademik.kelas.index')
            ->with('sukses', 'Kelas berhasil ditambahkan.');
    }

    public function update(UpdateKelasRequest $request, Kelas $kelas): RedirectResponse
    {
        $this->pastikanKelasCabang($kelas);
        $this->pastikanTahunAjaranAktif($kelas->tahunAjaran);

        $validated = $request->validated();

        $kelas->update([
            'jurusan_id' => $validated['jurusan_id'],
            'tingkat' => $validated['tingkat'],
            'nama' => $validated['nama'],
            'wali_kelas_id' => $validated['wali_kelas_id'] ?? null,
        ]);

        return redirect()
            ->route('admin-cabang.data-akademik.kelas.index')
            ->with('sukses', 'Kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas): RedirectResponse
    {
        $this->pastikanKelasCabang($kelas);
        $this->pastikanTahunAjaranAktif($kelas->tahunAjaran);

        if ($kelas->rombelSiswa()->exists() || $kelas->penugasanMengajar()->exists()) {
            return redirect()
                ->route('admin-cabang.data-akademik.kelas.index')
                ->with('gagal', 'Kelas tidak dapat dihapus karena masih memiliki siswa atau penugasan mengajar.');
        }

        $kelas->delete();

        return redirect()
            ->route('admin-cabang.data-akademik.kelas.index')
            ->with('sukses', 'Kelas berhasil dihapus.');
    }

    private function pastikanKelasCabang(Kelas $kelas): void
    {
        $kelas->loadMissing('tahunAjaran');

        if ($kelas->tahunAjaran->cabang_sekolah_id !== $this->cabangSekolahId()) {
            abort(404);
        }
    }
}
