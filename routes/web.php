<?php

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

    Route::view('/admin-pusat', 'dashboard.admin-pusat')
        ->middleware('role:admin_pusat')
        ->name('admin-pusat.dashboard');

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
