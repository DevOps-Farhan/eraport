<?php

namespace App\Http\Controllers\AdminCabang;

use App\Enums\AksiLogAkademik;
use App\Enums\StatusTahunAjaran;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StoreTahunAjaranRequest;
use App\Http\Requests\AdminCabang\UpdateTahunAjaranRequest;
use App\Models\TahunAjaran;
use App\Services\LogAkademikService;
use App\Services\TahunAjaranService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class TahunAjaranController extends Controller
{
    public function __construct(
        private TahunAjaranService $tahunAjaranService,
        private LogAkademikService $logAkademikService,
    ) {}

    public function index(): View
    {
        $cabangId = $this->cabangSekolahId();

        $tahunAjaran = TahunAjaran::query()
            ->with([
                'semester',
                'logAkademik' => fn ($query) => $query
                    ->with('pengguna')
                    ->orderByDesc('created_at')
                    ->limit(10),
            ])
            ->where('cabang_sekolah_id', $cabangId)
            ->orderByDesc('tanggal_mulai')
            ->paginate(10);

        return view('admin-cabang.data-akademik.tahun-ajaran.index', [
            'tahunAjaran' => $tahunAjaran,
        ]);
    }

    public function store(StoreTahunAjaranRequest $request): RedirectResponse
    {
        $cabangId = $this->cabangSekolahId();

        $tahunAjaran = TahunAjaran::query()->create([
            'cabang_sekolah_id' => $cabangId,
            'nama' => $request->string('nama')->value(),
            'tanggal_mulai' => $request->date('tanggal_mulai'),
            'status' => StatusTahunAjaran::Draft,
        ]);

        $this->logAkademikService->catat(
            $cabangId,
            AksiLogAkademik::TahunAjaranDibuat,
            $tahunAjaran->id,
            "Tahun ajaran {$tahunAjaran->nama} dibuat.",
        );

        return redirect()
            ->route('admin-cabang.data-akademik.tahun-ajaran.index')
            ->with('sukses', 'Tahun ajaran berhasil ditambahkan.');
    }

    public function update(UpdateTahunAjaranRequest $request, TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->pastikanMilikCabang($tahunAjaran);

        if (! $tahunAjaran->isDraft()) {
            throw ValidationException::withMessages([
                'nama' => 'Hanya tahun ajaran draft yang dapat diubah.',
            ]);
        }

        $tahunAjaran->update([
            'nama' => $request->string('nama')->value(),
            'tanggal_mulai' => $request->date('tanggal_mulai'),
        ]);

        $this->logAkademikService->catat(
            $tahunAjaran->cabang_sekolah_id,
            AksiLogAkademik::TahunAjaranDiubah,
            $tahunAjaran->id,
            "Tahun ajaran {$tahunAjaran->nama} diperbarui.",
        );

        return redirect()
            ->route('admin-cabang.data-akademik.tahun-ajaran.index')
            ->with('sukses', 'Tahun ajaran berhasil diperbarui.');
    }

    public function destroy(TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->pastikanMilikCabang($tahunAjaran);

        if (! $tahunAjaran->isDraft()) {
            return redirect()
                ->route('admin-cabang.data-akademik.tahun-ajaran.index')
                ->with('gagal', 'Hanya tahun ajaran draft yang dapat dihapus.');
        }

        $nama = $tahunAjaran->nama;
        $cabangId = $tahunAjaran->cabang_sekolah_id;
        $tahunAjaranId = $tahunAjaran->id;

        $tahunAjaran->delete();

        $this->logAkademikService->catat(
            $cabangId,
            AksiLogAkademik::TahunAjaranDihapus,
            $tahunAjaranId,
            "Tahun ajaran {$nama} dihapus.",
        );

        return redirect()
            ->route('admin-cabang.data-akademik.tahun-ajaran.index')
            ->with('sukses', 'Tahun ajaran berhasil dihapus.');
    }

    public function aktifkan(TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->pastikanMilikCabang($tahunAjaran);

        try {
            $this->tahunAjaranService->aktifkan($tahunAjaran);
        } catch (ValidationException $e) {
            return redirect()
                ->route('admin-cabang.data-akademik.tahun-ajaran.index')
                ->withErrors($e->errors())
                ->with('gagal', collect($e->errors())->flatten()->first());
        }

        return redirect()
            ->route('admin-cabang.data-akademik.tahun-ajaran.index')
            ->with('sukses', 'Tahun ajaran diaktifkan. Semester ganjil dimulai.');
    }

    public function pindahKeSemesterGenap(TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->pastikanMilikCabang($tahunAjaran);

        try {
            $this->tahunAjaranService->pindahKeSemesterGenap($tahunAjaran);
        } catch (ValidationException $e) {
            return redirect()
                ->route('admin-cabang.data-akademik.tahun-ajaran.index')
                ->with('gagal', collect($e->errors())->flatten()->first());
        }

        return redirect()
            ->route('admin-cabang.data-akademik.tahun-ajaran.index')
            ->with('sukses', 'Berhasil pindah ke semester genap.');
    }

    public function akhiriSemesterGenap(TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->pastikanMilikCabang($tahunAjaran);

        try {
            $this->tahunAjaranService->akhiriSemesterGenap($tahunAjaran);
        } catch (ValidationException $e) {
            return redirect()
                ->route('admin-cabang.data-akademik.tahun-ajaran.index')
                ->with('gagal', collect($e->errors())->flatten()->first());
        }

        return redirect()
            ->route('admin-cabang.data-akademik.tahun-ajaran.index')
            ->with('sukses', 'Semester genap diselesaikan.');
    }

    public function tutup(TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->pastikanMilikCabang($tahunAjaran);

        try {
            $this->tahunAjaranService->tutup($tahunAjaran);
        } catch (ValidationException $e) {
            return redirect()
                ->route('admin-cabang.data-akademik.tahun-ajaran.index')
                ->with('gagal', collect($e->errors())->flatten()->first());
        }

        return redirect()
            ->route('admin-cabang.data-akademik.tahun-ajaran.index')
            ->with('sukses', 'Tahun ajaran ditutup dan diarsipkan.');
    }

    public function kembalikanKeGanjil(TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->pastikanMilikCabang($tahunAjaran);

        try {
            $this->tahunAjaranService->kembalikanKeSemesterGanjil($tahunAjaran);
        } catch (ValidationException $e) {
            return redirect()
                ->route('admin-cabang.data-akademik.tahun-ajaran.index')
                ->with('gagal', collect($e->errors())->flatten()->first());
        }

        return redirect()
            ->route('admin-cabang.data-akademik.tahun-ajaran.index')
            ->with('sukses', 'Berhasil dikembalikan ke semester ganjil.');
    }

    public function bukaKembaliGenap(TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->pastikanMilikCabang($tahunAjaran);

        try {
            $this->tahunAjaranService->bukaKembaliSemesterGenap($tahunAjaran);
        } catch (ValidationException $e) {
            return redirect()
                ->route('admin-cabang.data-akademik.tahun-ajaran.index')
                ->with('gagal', collect($e->errors())->flatten()->first());
        }

        return redirect()
            ->route('admin-cabang.data-akademik.tahun-ajaran.index')
            ->with('sukses', 'Semester genap dibuka kembali.');
    }

    public function bukaKembali(TahunAjaran $tahunAjaran): RedirectResponse
    {
        $this->pastikanMilikCabang($tahunAjaran);

        try {
            $this->tahunAjaranService->bukaKembaliTahunAjaran($tahunAjaran);
        } catch (ValidationException $e) {
            return redirect()
                ->route('admin-cabang.data-akademik.tahun-ajaran.index')
                ->with('gagal', collect($e->errors())->flatten()->first());
        }

        return redirect()
            ->route('admin-cabang.data-akademik.tahun-ajaran.index')
            ->with('sukses', 'Tahun ajaran dibuka kembali.');
    }

    private function cabangSekolahId(): int
    {
        $cabangId = auth()->user()->cabang_sekolah_id;

        if (! $cabangId) {
            abort(403, 'Admin cabang tidak terhubung ke cabang sekolah.');
        }

        return $cabangId;
    }

    private function pastikanMilikCabang(TahunAjaran $tahunAjaran): void
    {
        if ($tahunAjaran->cabang_sekolah_id !== $this->cabangSekolahId()) {
            abort(404);
        }
    }
}
