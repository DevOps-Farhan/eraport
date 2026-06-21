<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Guru\Concerns\MenanganiPenugasanGuru;
use App\Http\Requests\Guru\StoreTujuanPembelajaranRequest;
use App\Http\Requests\Guru\UpdateTujuanPembelajaranRequest;
use App\Models\PenugasanMengajar;
use App\Models\TujuanPembelajaran;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TujuanPembelajaranController extends Controller
{
    use MenanganiPenugasanGuru;

    public function list(): View
    {
        $penugasanList = $this->penugasanGuru();

        return view('guru.kelola-nilai.tujuan-pembelajaran.list', [
            'penugasanList' => $penugasanList,
        ]);
    }

    public function index(PenugasanMengajar $penugasan): View
    {
        $penugasanAktif = $this->pastikanPenugasanGuru($penugasan);

        $tujuanPembelajaran = TujuanPembelajaran::query()
            ->where('penugasan_mengajar_id', $penugasanAktif->id)
            ->orderBy('urutan')
            ->orderBy('kode')
            ->get();

        return view('guru.kelola-nilai.tujuan-pembelajaran.index', [
            'penugasanAktif' => $penugasanAktif,
            'tujuanPembelajaran' => $tujuanPembelajaran,
            'dapatDiedit' => $this->semesterDapatDiedit(),
        ]);
    }

    public function store(StoreTujuanPembelajaranRequest $request, PenugasanMengajar $penugasan): RedirectResponse
    {
        if (! $this->semesterDapatDiedit()) {
            return back()->with('gagal', 'Semester tidak sedang berjalan. Data tidak dapat diubah.');
        }

        $penugasan = $this->pastikanPenugasanGuru($penugasan);

        TujuanPembelajaran::query()->create([
            'penugasan_mengajar_id' => $penugasan->id,
            'kode' => $request->string('kode')->value(),
            'deskripsi_kompetensi' => $request->string('deskripsi_kompetensi')->value(),
            'urutan' => $request->integer('urutan'),
        ]);

        return redirect()
            ->route('guru.kelola-nilai.tujuan-pembelajaran.show', $penugasan)
            ->with('sukses', 'Tujuan pembelajaran berhasil ditambahkan.');
    }

    public function update(UpdateTujuanPembelajaranRequest $request, TujuanPembelajaran $tujuanPembelajaran): RedirectResponse
    {
        if (! $this->semesterDapatDiedit()) {
            return back()->with('gagal', 'Semester tidak sedang berjalan. Data tidak dapat diubah.');
        }

        $tujuanPembelajaran = $this->pastikanTujuanPembelajaranGuru($tujuanPembelajaran);

        $tujuanPembelajaran->update([
            'kode' => $request->string('kode')->value(),
            'deskripsi_kompetensi' => $request->string('deskripsi_kompetensi')->value(),
            'urutan' => $request->integer('urutan'),
        ]);

        return redirect()
            ->route('guru.kelola-nilai.tujuan-pembelajaran.show', $tujuanPembelajaran->penugasanMengajar)
            ->with('sukses', 'Tujuan pembelajaran berhasil diperbarui.');
    }

    public function destroy(TujuanPembelajaran $tujuanPembelajaran): RedirectResponse
    {
        if (! $this->semesterDapatDiedit()) {
            return back()->with('gagal', 'Semester tidak sedang berjalan. Data tidak dapat diubah.');
        }

        $tujuanPembelajaran = $this->pastikanTujuanPembelajaranGuru($tujuanPembelajaran);
        $penugasan = $tujuanPembelajaran->penugasanMengajar;

        if ($tujuanPembelajaran->nilaiTujuanPembelajaran()->exists()) {
            return redirect()
                ->route('guru.kelola-nilai.tujuan-pembelajaran.show', $penugasan)
                ->with('gagal', 'Tujuan pembelajaran tidak dapat dihapus karena sudah memiliki nilai.');
        }

        $tujuanPembelajaran->delete();

        return redirect()
            ->route('guru.kelola-nilai.tujuan-pembelajaran.show', $penugasan)
            ->with('sukses', 'Tujuan pembelajaran berhasil dihapus.');
    }
}
