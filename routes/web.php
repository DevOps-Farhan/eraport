<?php

use App\Http\Controllers\AdminPusat\CabangSekolahController;
use App\Http\Controllers\AdminPusat\DashboardController;
use App\Http\Controllers\AdminPusat\PenggunaController;
use App\Http\Controllers\Auth\LoginController;
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
            Route::get('/pengguna/pembina', [PenggunaController::class, 'pembina'])->name('pengguna.pembina');
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
