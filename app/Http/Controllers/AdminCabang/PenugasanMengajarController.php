<?php

namespace App\Http\Controllers\AdminCabang;

use App\Enums\PeranPengguna;
use App\Http\Controllers\AdminCabang\Concerns\MenanganiScopeCabang;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StorePenugasanMengajarRequest;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PenugasanMengajar;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PenugasanMengajarController extends Controller
{
    use MenanganiScopeCabang;

    public function index(): View
    {
        $cabangId = $this->cabangSekolahId();
        $tahunAjaran = app(\App\Services\KonteksAkademik::class)->tahunAjaranAktif($cabangId);

        $penugasan = PenugasanMengajar::query()->whereRaw('1 = 0')->paginate(10);
        $guruList = User::query()
            ->where('cabang_sekolah_id', $cabangId)
            ->where('peran', PeranPengguna::Guru)
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();
        $mataPelajaranList = MataPelajaran::query()
            ->where('cabang_sekolah_id', $cabangId)
            ->where('aktif', true)
            ->orderBy('nama')
            ->get();
        $kelasList = $tahunAjaran
            ? Kelas::query()
                ->with('jurusan')
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->orderBy('nama')
                ->get()
            : collect();

        if ($tahunAjaran) {
            $penugasan = PenugasanMengajar::query()
                ->with(['guru', 'mataPelajaran', 'kelas.jurusan'])
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->orderBy('id')
                ->paginate(10);
        }

        return view('admin-cabang.data-akademik.penugasan-mengajar.index', [
            'penugasan' => $penugasan,
            'tahunAjaran' => $tahunAjaran,
            'guruList' => $guruList,
            'mataPelajaranList' => $mataPelajaranList,
            'kelasList' => $kelasList,
        ]);
    }

    public function store(StorePenugasanMengajarRequest $request): RedirectResponse
    {
        $tahunAjaran = $this->tahunAjaranAktif();
        $request->merge(['tahun_ajaran_id' => $tahunAjaran->id]);

        try {
            PenugasanMengajar::query()->create($request->validated());
        } catch (\Illuminate\Database\QueryException) {
            return redirect()
                ->route('admin-cabang.data-akademik.penugasan-mengajar.index')
                ->with('gagal', 'Penugasan mengajar sudah ada untuk kombinasi guru, mata pelajaran, dan kelas ini.');
        }

        return redirect()
            ->route('admin-cabang.data-akademik.penugasan-mengajar.index')
            ->with('sukses', 'Penugasan mengajar berhasil ditambahkan.');
    }

    public function destroy(PenugasanMengajar $penugasanMengajar): RedirectResponse
    {
        $this->pastikanPenugasanCabang($penugasanMengajar);
        $this->pastikanTahunAjaranAktif($penugasanMengajar->tahunAjaran);

        $penugasanMengajar->delete();

        return redirect()
            ->route('admin-cabang.data-akademik.penugasan-mengajar.index')
            ->with('sukses', 'Penugasan mengajar berhasil dihapus.');
    }

    private function pastikanPenugasanCabang(PenugasanMengajar $penugasan): void
    {
        $penugasan->loadMissing('tahunAjaran');

        if ($penugasan->tahunAjaran->cabang_sekolah_id !== $this->cabangSekolahId()) {
            abort(404);
        }
    }
}
