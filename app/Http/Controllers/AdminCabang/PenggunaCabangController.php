<?php

namespace App\Http\Controllers\AdminCabang;

use App\Enums\PeranPengguna;
use App\Http\Controllers\AdminCabang\Concerns\MenanganiScopeCabang;
use App\Http\Controllers\Controller;
use App\Http\Requests\AdminCabang\StorePenggunaCabangRequest;
use App\Http\Requests\AdminCabang\UpdatePenggunaCabangRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PenggunaCabangController extends Controller
{
    use MenanganiScopeCabang;

    public function guru(Request $request): View
    {
        return $this->index($request, PeranPengguna::Guru);
    }

    public function pembina(Request $request): View
    {
        return $this->index($request, PeranPengguna::PembinaEkstrakurikuler);
    }

    public function store(StorePenggunaCabangRequest $request): RedirectResponse
    {
        $peran = $this->resolvePeranFromTipe($request->route('tipe'));

        User::query()->create([
            'username' => $request->string('username')->value(),
            'nama' => $request->string('nama')->value(),
            'email' => $request->string('email')->toString() ?: null,
            'password' => $request->string('password')->value(),
            'peran' => $peran,
            'cabang_sekolah_id' => $this->cabangSekolahId(),
            'aktif' => $request->boolean('aktif', true),
        ]);

        return redirect()
            ->route($this->routeNameForPeran($peran))
            ->with('sukses', 'Pengguna berhasil ditambahkan.');
    }

    public function update(UpdatePenggunaCabangRequest $request, User $pengguna): RedirectResponse
    {
        $peran = $this->resolvePeranFromTipe($request->route('tipe'));
        $this->pastikanPenggunaCabang($pengguna, $peran);

        $data = [
            'username' => $request->string('username')->value(),
            'nama' => $request->string('nama')->value(),
            'email' => $request->string('email')->toString() ?: null,
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
        $this->pastikanPenggunaCabang($pengguna, $peran);

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
        $cabangId = $this->cabangSekolahId();
        $cari = $request->string('cari')->trim()->value();

        $pengguna = User::query()
            ->where('cabang_sekolah_id', $cabangId)
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

        return view('admin-cabang.master-data.pengguna.index', [
            'pengguna' => $pengguna,
            'judul' => $peran->label(),
            'peran' => $peran,
            'routeName' => $this->routeNameForPeran($peran),
            'tipe' => $this->tipeForPeran($peran),
            'cari' => $cari,
        ]);
    }

    private function pastikanPenggunaCabang(User $pengguna, PeranPengguna $peran): void
    {
        if ($pengguna->cabang_sekolah_id !== $this->cabangSekolahId() || $pengguna->peran !== $peran) {
            abort(404);
        }
    }

    private function resolvePeranFromTipe(string $tipe): PeranPengguna
    {
        return match ($tipe) {
            'guru' => PeranPengguna::Guru,
            'pembina' => PeranPengguna::PembinaEkstrakurikuler,
            default => throw new NotFoundHttpException,
        };
    }

    private function routeNameForPeran(PeranPengguna $peran): string
    {
        return match ($peran) {
            PeranPengguna::Guru => 'admin-cabang.master-data.guru.index',
            PeranPengguna::PembinaEkstrakurikuler => 'admin-cabang.master-data.pembina.index',
            default => 'admin-cabang.dashboard',
        };
    }

    private function tipeForPeran(PeranPengguna $peran): string
    {
        return match ($peran) {
            PeranPengguna::Guru => 'guru',
            PeranPengguna::PembinaEkstrakurikuler => 'pembina',
            default => 'guru',
        };
    }
}
