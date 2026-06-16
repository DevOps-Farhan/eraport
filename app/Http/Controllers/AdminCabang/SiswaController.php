<?php

namespace App\Http\Controllers\AdminCabang;

use App\Enums\StatusRombel;
use App\Http\Controllers\AdminCabang\Concerns\MenanganiScopeCabang;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\AssignRombelSiswaRequest;
use App\Http\Requests\AdminCabang\StoreSiswaRequest;
use App\Http\Requests\AdminCabang\UpdateSiswaRequest;
use App\Models\Kelas;
use App\Models\RombelSiswa;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SiswaController extends Controller
{
    use MenanganiScopeCabang;

    public function index(): View
    {
        $cabangId = $this->cabangSekolahId();
        $tahunAjaran = app(\App\Services\KonteksAkademik::class)->tahunAjaranAktif($cabangId);

        $siswa = Siswa::query()
            ->with([
                'rombelSiswa' => fn ($query) => $tahunAjaran
                    ? $query->where('tahun_ajaran_id', $tahunAjaran->id)->with('kelas')
                    : $query->whereRaw('1 = 0'),
            ])
            ->where('cabang_sekolah_id', $cabangId)
            ->orderBy('nama')
            ->paginate(10);

        $kelasList = $tahunAjaran
            ? Kelas::query()
                ->where('tahun_ajaran_id', $tahunAjaran->id)
                ->orderBy('nama')
                ->get()
            : collect();

        return view('admin-cabang.master-data.siswa.index', [
            'siswa' => $siswa,
            'tahunAjaran' => $tahunAjaran,
            'kelasList' => $kelasList,
        ]);
    }

    public function store(StoreSiswaRequest $request): RedirectResponse
    {
        Siswa::query()->create([
            'cabang_sekolah_id' => $this->cabangSekolahId(),
            'nis' => $request->string('nis')->value(),
            'nisn' => $request->string('nisn')->value() ?: null,
            'nama' => $request->string('nama')->value(),
            'jenis_kelamin' => $request->string('jenis_kelamin')->value(),
            'tanggal_lahir' => $request->date('tanggal_lahir'),
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()
            ->route('admin-cabang.master-data.siswa.index')
            ->with('sukses', 'Siswa berhasil ditambahkan.');
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $this->pastikanMilikCabang($siswa);

        $siswa->update([
            'nis' => $request->string('nis')->value(),
            'nisn' => $request->string('nisn')->value() ?: null,
            'nama' => $request->string('nama')->value(),
            'jenis_kelamin' => $request->string('jenis_kelamin')->value(),
            'tanggal_lahir' => $request->date('tanggal_lahir'),
            'aktif' => $request->boolean('aktif', false),
        ]);

        return redirect()
            ->route('admin-cabang.master-data.siswa.index')
            ->with('sukses', 'Siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa): RedirectResponse
    {
        $this->pastikanMilikCabang($siswa);

        if ($siswa->rombelSiswa()->exists()) {
            return redirect()
                ->route('admin-cabang.master-data.siswa.index')
                ->with('gagal', 'Siswa tidak dapat dihapus karena masih terdaftar di rombel.');
        }

        $siswa->delete();

        return redirect()
            ->route('admin-cabang.master-data.siswa.index')
            ->with('sukses', 'Siswa berhasil dihapus.');
    }

    public function assignRombel(AssignRombelSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $this->pastikanMilikCabang($siswa);

        $tahunAjaran = $this->tahunAjaranAktif();
        $request->merge(['tahun_ajaran_id' => $tahunAjaran->id]);
        $validated = $request->validated();

        RombelSiswa::query()->updateOrCreate(
            [
                'tahun_ajaran_id' => $tahunAjaran->id,
                'siswa_id' => $siswa->id,
            ],
            [
                'kelas_id' => $validated['kelas_id'],
                'status' => StatusRombel::Aktif,
            ],
        );

        return redirect()
            ->route('admin-cabang.master-data.siswa.index')
            ->with('sukses', 'Siswa berhasil ditempatkan ke kelas.');
    }
}
