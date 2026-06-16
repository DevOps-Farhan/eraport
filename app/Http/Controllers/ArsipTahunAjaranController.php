<?php

namespace App\Http\Controllers;

use App\Enums\StatusTahunAjaran;
use App\Models\LogAkademik;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ArsipTahunAjaranController extends Controller
{
    public function index(Request $request): View
    {
        $cabangId = $this->cabangSekolahId();

        $tahunAjaran = TahunAjaran::query()
            ->with('semester')
            ->where('cabang_sekolah_id', $cabangId)
            ->where('status', StatusTahunAjaran::Ditutup)
            ->orderByDesc('tanggal_selesai')
            ->paginate(10);

        return view('arsip.tahun-ajaran.index', [
            'tahunAjaran' => $tahunAjaran,
            'dashboardRoute' => auth()->user()->dashboardRouteName(),
        ]);
    }

    public function show(TahunAjaran $tahunAjaran): View
    {
        $this->pastikanArsipCabang($tahunAjaran);

        $tahunAjaran->load('semester');

        $logAkademik = LogAkademik::query()
            ->with('pengguna')
            ->where('tahun_ajaran_id', $tahunAjaran->id)
            ->orderByDesc('created_at')
            ->limit(20)
            ->get();

        return view('arsip.tahun-ajaran.show', [
            'tahunAjaran' => $tahunAjaran,
            'logAkademik' => $logAkademik,
            'dashboardRoute' => auth()->user()->dashboardRouteName(),
        ]);
    }

    private function cabangSekolahId(): int
    {
        $cabangId = auth()->user()->cabang_sekolah_id;

        if (! $cabangId) {
            abort(403, 'Akun tidak terhubung ke cabang sekolah.');
        }

        return $cabangId;
    }

    private function pastikanArsipCabang(TahunAjaran $tahunAjaran): void
    {
        if ($tahunAjaran->cabang_sekolah_id !== $this->cabangSekolahId()) {
            abort(404);
        }

        if (! $tahunAjaran->isDitutup()) {
            abort(404);
        }
    }
}
