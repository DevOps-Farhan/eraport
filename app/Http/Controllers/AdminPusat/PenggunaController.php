<?php

namespace App\Http\Controllers\AdminPusat;

use App\Enums\PeranPengguna;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminPusat\StorePenggunaRequest;
use App\Http\Requests\AdminPusat\UpdatePenggunaRequest;
use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PenggunaController extends Controller
{
    public function guru(Request $request): View
    {
        return $this->index($request, PeranPengguna::Guru);
    }

    public function adminCabang(Request $request): View
    {
        return $this->index($request, PeranPengguna::AdminCabang);
    }

    public function pembina(Request $request): View
    {
        return $this->index($request, PeranPengguna::PembinaEkstrakurikuler);
    }

    public function store(StorePenggunaRequest $request): RedirectResponse
    {
        $peran = $this->resolvePeranFromTipe($request->route('tipe'));

        User::query()->create([
            'username' => $request->string('username')->value(),
            'nama' => $request->string('nama')->value(),
            'email' => $request->string('email')->toString() ?: null,
            'password' => $request->string('password')->value(),
            'peran' => $peran,
            'cabang_sekolah_id' => $request->integer('cabang_sekolah_id'),
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()
            ->route($this->routeNameForPeran($peran))
            ->with('sukses', 'Pengguna berhasil ditambahkan.');
    }

    public function update(UpdatePenggunaRequest $request, User $pengguna): RedirectResponse
    {
        $peran = $this->resolvePeranFromTipe($request->route('tipe'));
        $this->ensurePenggunaHasPeran($pengguna, $peran);

        $data = [
            'username' => $request->string('username')->value(),
            'nama' => $request->string('nama')->value(),
            'email' => $request->string('email')->toString() ?: null,
            'cabang_sekolah_id' => $request->integer('cabang_sekolah_id'),
            'aktif' => $request->boolean('aktif', false),
        ];

        if ($request->filled('password')) {
            $data['password'] = $request->string('password')->value();
        }

        $pengguna->update($data);

        return redirect()
            ->route($this->routeNameForPeran($peran))
            ->with('sukses', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(Request $request, User $pengguna): RedirectResponse
    {
        $peran = $this->resolvePeranFromTipe($request->route('tipe'));
        $this->ensurePenggunaHasPeran($pengguna, $peran);

        if ($pengguna->is($request->user())) {
            return redirect()
                ->route($this->routeNameForPeran($peran))
                ->with('gagal', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $pengguna->delete();

        return redirect()
            ->route($this->routeNameForPeran($peran))
            ->with('sukses', 'Pengguna berhasil dihapus.');
    }

    private function index(Request $request, PeranPengguna $peran): View
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
            'judul' => $peran->label(),
            'peran' => $peran,
            'routeName' => $this->routeNameForPeran($peran),
            'tipe' => $this->tipeForPeran($peran),
            'cari' => $cari,
            'cabangSekolah' => CabangSekolah::query()->where('aktif', true)->orderBy('nama')->get(),
        ]);
    }

    private function resolvePeranFromTipe(string $tipe): PeranPengguna
    {
        return match ($tipe) {
            'guru' => PeranPengguna::Guru,
            'admin-cabang' => PeranPengguna::AdminCabang,
            'pembina' => PeranPengguna::PembinaEkstrakurikuler,
            default => throw new NotFoundHttpException,
        };
    }

    private function ensurePenggunaHasPeran(User $pengguna, PeranPengguna $peran): void
    {
        if ($pengguna->peran !== $peran) {
            abort(404);
        }
    }

    private function routeNameForPeran(PeranPengguna $peran): string
    {
        return match ($peran) {
            PeranPengguna::Guru => 'admin-pusat.pengguna.guru',
            PeranPengguna::AdminCabang => 'admin-pusat.pengguna.admin-cabang',
            PeranPengguna::PembinaEkstrakurikuler => 'admin-pusat.pengguna.pembina',
            default => 'admin-pusat.dashboard',
        };
    }

    private function tipeForPeran(PeranPengguna $peran): string
    {
        return match ($peran) {
            PeranPengguna::Guru => 'guru',
            PeranPengguna::AdminCabang => 'admin-cabang',
            PeranPengguna::PembinaEkstrakurikuler => 'pembina',
            default => 'guru',
        };
    }
}
