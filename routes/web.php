<?php

use App\Http\Controllers\AdminPusat\CabangSekolahController;
use App\Http\Controllers\AdminPusat\DashboardController;
use App\Http\Controllers\AdminPusat\PenggunaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfilController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->dashboardRouteName());
    }

    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'create'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
});

Route::middleware(['auth', 'active'])->group(function () {
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    Route::get('/profil', [ProfilController::class, 'edit'])->name('profil.edit');
    Route::put('/profil', [ProfilController::class, 'update'])->name('profil.update');
    Route::put('/profil/password', [ProfilController::class, 'updatePassword'])->name('profil.password.update');

    Route::prefix('admin-pusat')
        ->middleware('role:admin_pusat')
        ->name('admin-pusat.')
        ->group(function () {
            Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

            Route::get('/cabang-sekolah', [CabangSekolahController::class, 'index'])->name('cabang-sekolah.index');
            Route::post('/cabang-sekolah', [CabangSekolahController::class, 'store'])->name('cabang-sekolah.store');
            Route::put('/cabang-sekolah/{cabang_sekolah}', [CabangSekolahController::class, 'update'])->name('cabang-sekolah.update');
            Route::delete('/cabang-sekolah/{cabang_sekolah}', [CabangSekolahController::class, 'destroy'])->name('cabang-sekolah.destroy');

            Route::get('/pengguna/guru', [PenggunaController::class, 'guru'])->name('pengguna.guru');
            Route::post('/pengguna/guru', [PenggunaController::class, 'store'])
                ->defaults('tipe', 'guru')
                ->name('pengguna.guru.store');
            Route::put('/pengguna/guru/{pengguna}', [PenggunaController::class, 'update'])
                ->defaults('tipe', 'guru')
                ->name('pengguna.guru.update');
            Route::delete('/pengguna/guru/{pengguna}', [PenggunaController::class, 'destroy'])
                ->defaults('tipe', 'guru')
                ->name('pengguna.guru.destroy');

            Route::get('/pengguna/admin-cabang', [PenggunaController::class, 'adminCabang'])->name('pengguna.admin-cabang');
            Route::post('/pengguna/admin-cabang', [PenggunaController::class, 'store'])
                ->defaults('tipe', 'admin-cabang')
                ->name('pengguna.admin-cabang.store');
            Route::put('/pengguna/admin-cabang/{pengguna}', [PenggunaController::class, 'update'])
                ->defaults('tipe', 'admin-cabang')
                ->name('pengguna.admin-cabang.update');
            Route::delete('/pengguna/admin-cabang/{pengguna}', [PenggunaController::class, 'destroy'])
                ->defaults('tipe', 'admin-cabang')
                ->name('pengguna.admin-cabang.destroy');

            Route::get('/pengguna/pembina', [PenggunaController::class, 'pembina'])->name('pengguna.pembina');
            Route::post('/pengguna/pembina', [PenggunaController::class, 'store'])
                ->defaults('tipe', 'pembina')
                ->name('pengguna.pembina.store');
            Route::put('/pengguna/pembina/{pengguna}', [PenggunaController::class, 'update'])
                ->defaults('tipe', 'pembina')
                ->name('pengguna.pembina.update');
            Route::delete('/pengguna/pembina/{pengguna}', [PenggunaController::class, 'destroy'])
                ->defaults('tipe', 'pembina')
                ->name('pengguna.pembina.destroy');
        });

    Route::view('/admin-cabang', 'dashboard.admin-cabang')
        ->middleware('role:admin_cabang')
        ->name('admin-cabang.dashboard');

    Route::view('/guru', 'dashboard.guru')
        ->middleware('role:guru')
        ->name('guru.dashboard');

    Route::view('/pembina', 'dashboard.pembina')
        ->middleware('role:pembina_ekstrakurikuler')
        ->name('pembina.dashboard');
});
