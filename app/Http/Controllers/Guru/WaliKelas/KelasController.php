<?php

namespace App\Http\Controllers\Guru\WaliKelas;

use App\Enums\StatusRombel;
use App\Http\Controllers\Concerns\MapsAtributSiswa;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Guru\Concerns\MenanganiWaliKelas;
use App\Http\Requests\Guru\StoreSiswaWaliKelasRequest;
use App\Http\Requests\Guru\UpdateSiswaWaliKelasRequest;
use App\Models\Kelas;
use App\Models\RombelSiswa;
use App\Models\Siswa;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class KelasController extends Controller
{
    use MapsAtributSiswa, MenanganiWaliKelas;

    public function list(): View
    {
        $kelasList = $this->daftarKelasWali();

        return view('guru.wali-kelas.kelas.list', [
            'kelasList' => $kelasList,
        ]);
    }

    public function index(Kelas $kelas): View
    {
        $this->pastikanAksesKelas($kelas);

        $siswaList = Siswa::query()
            ->whereHas('rombelSiswa', function ($query) use ($kelas) {
                $query->where('kelas_id', $kelas->id)
                    ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                    ->where('status', StatusRombel::Aktif);
            })
            ->orderBy('nama')
            ->get();

        return view('guru.wali-kelas.kelas.index', [
            'kelas' => $kelas,
            'siswaList' => $siswaList,
            'dapatDiedit' => true,
        ]);
    }

    public function storeSiswa(StoreSiswaWaliKelasRequest $request, Kelas $kelas): RedirectResponse
    {
        $this->pastikanAksesKelas($kelas);

        $siswa = Siswa::query()->create([
            'cabang_sekolah_id' => $this->cabangSekolahId(),
            ...$this->atributSiswaDariRequest($request),
            'aktif' => true,
        ]);

        RombelSiswa::query()->create([
            'tahun_ajaran_id' => $kelas->tahun_ajaran_id,
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
            'status' => StatusRombel::Aktif,
        ]);

        return redirect()
            ->route('guru.wali-kelas.kelas.show', $kelas)
            ->with('sukses', 'Siswa berhasil ditambahkan ke kelas.');
    }

    public function updateSiswa(UpdateSiswaWaliKelasRequest $request, Kelas $kelas, Siswa $siswa): RedirectResponse
    {
        $this->pastikanSiswaDiKelasWali($siswa, $kelas);

        $siswa->update($this->atributSiswaDariRequest($request));

        return redirect()
            ->route('guru.wali-kelas.kelas.show', $kelas)
            ->with('sukses', 'Data siswa berhasil diperbarui.');
    }

    public function destroySiswa(Kelas $kelas, Siswa $siswa): RedirectResponse
    {
        $this->pastikanSiswaDiKelasWali($siswa, $kelas);

        RombelSiswa::query()
            ->where('kelas_id', $kelas->id)
            ->where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
            ->where('siswa_id', $siswa->id)
            ->delete();

        return redirect()
            ->route('guru.wali-kelas.kelas.show', $kelas)
            ->with('sukses', 'Siswa berhasil dihapus dari kelas.');
    }
}
