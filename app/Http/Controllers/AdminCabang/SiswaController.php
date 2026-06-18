<?php

namespace App\Http\Controllers\AdminCabang;

use App\Enums\StatusRombel;
use App\Http\Controllers\AdminCabang\Concerns\MenanganiScopeCabang;
use App\Http\Controllers\Concerns\MapsAtributSiswa;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\AssignRombelSiswaRequest;
use App\Http\Requests\AdminCabang\ImportSiswaCsvRequest;
use App\Http\Requests\AdminCabang\StoreSiswaRequest;
use App\Http\Requests\AdminCabang\UpdateSiswaRequest;
use App\Models\Kelas;
use App\Models\RombelSiswa;
use App\Models\Siswa;
use App\Services\ImportSiswaCsvService;
use App\Support\SiswaCsv;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SiswaController extends Controller
{
    use MapsAtributSiswa, MenanganiScopeCabang;

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
            ...$this->atributSiswaDariRequest($request, includeAktif: true),
        ]);

        return redirect()
            ->route('admin-cabang.master-data.siswa.index')
            ->with('sukses', 'Siswa berhasil ditambahkan.');
    }

    public function unduhTemplateCsv(ImportSiswaCsvService $importSiswaCsvService): StreamedResponse
    {
        $namaFile = SiswaCsv::namaFileTemplate();

        return response()->streamDownload(
            fn () => print ($importSiswaCsvService->kontenTemplate()),
            $namaFile,
            ['Content-Type' => 'text/csv; charset=UTF-8'],
        );
    }

    public function importCsv(ImportSiswaCsvRequest $request, ImportSiswaCsvService $importSiswaCsvService): RedirectResponse
    {
        try {
            $hasil = $importSiswaCsvService->impor(
                $request->file('file')->getPathname(),
                $this->cabangSekolahId(),
            );
        } catch (ValidationException $e) {
            return redirect()
                ->route('admin-cabang.master-data.siswa.index')
                ->withErrors($e->errors())
                ->with('gagal', collect($e->errors())->flatten()->first());
        }

        if ($hasil['kesalahan'] !== []) {
            return redirect()
                ->route('admin-cabang.master-data.siswa.index')
                ->with('gagal', 'Import gagal. Perbaiki kesalahan berikut lalu coba lagi.')
                ->with('import_kesalahan', $hasil['kesalahan']);
        }

        return redirect()
            ->route('admin-cabang.master-data.siswa.index')
            ->with('sukses', "{$hasil['berhasil']} siswa berhasil diimpor.");
    }

    public function update(UpdateSiswaRequest $request, Siswa $siswa): RedirectResponse
    {
        $this->pastikanMilikCabang($siswa);

        $siswa->update($this->atributSiswaDariRequest($request, includeAktif: true));

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
