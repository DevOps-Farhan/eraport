<?php

namespace App\Http\Controllers\AdminPusat;

use App\Enums\PeranPengguna;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenggunaController extends Controller
{
    public function guru(Request $request): View
    {
        return $this->index($request, PeranPengguna::Guru, 'Guru', 'admin-pusat.pengguna.guru');
    }

    public function pembina(Request $request): View
    {
        return $this->index($request, PeranPengguna::PembinaEkstrakurikuler, 'Pembina Ekstrakurikuler', 'admin-pusat.pengguna.pembina');
    }

    private function index(Request $request, PeranPengguna $peran, string $judul, string $routeName): View
    {
        $cari = $request->string('cari')->trim()->value();

        $pengguna = User::query()
            ->with('cabangSekolah')
            ->where('peran', $peran)
            ->when($cari !== '', function ($query) use ($cari) {
                $query->where(function ($query) use ($cari) {
                    $query->where('username', 'like', "%{$cari}%")
                        ->orWhere('nama', 'like', "%{$cari}%")
                        ->orWhere('email', 'like', "%{$cari}%");
                });
            })
            ->orderBy('nama')
            ->paginate(10)
            ->withQueryString();

        return view('admin-pusat.pengguna.index', [
            'pengguna' => $pengguna,
            'judul' => $judul,
            'peran' => $peran,
            'routeName' => $routeName,
            'cari' => $cari,
        ]);
    }
}
