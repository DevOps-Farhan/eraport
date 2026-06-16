<?php

use App\Http\Controllers\AdminCabang\TahunAjaranController;
use App\Http\Controllers\ArsipTahunAjaranController;
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

    Route::prefix('admin-cabang')
        ->middleware('role:admin_cabang')
        ->name('admin-cabang.')
        ->group(function () {
            Route::view('/', 'admin-cabang.dashboard')->name('dashboard');

            Route::prefix('data-akademik')->name('data-akademik.')->group(function () {
                Route::get('/tahun-ajaran', [TahunAjaranController::class, 'index'])->name('tahun-ajaran.index');
                Route::post('/tahun-ajaran', [TahunAjaranController::class, 'store'])->name('tahun-ajaran.store');
                Route::put('/tahun-ajaran/{tahun_ajaran}', [TahunAjaranController::class, 'update'])->name('tahun-ajaran.update');
                Route::delete('/tahun-ajaran/{tahun_ajaran}', [TahunAjaranController::class, 'destroy'])->name('tahun-ajaran.destroy');
                Route::post('/tahun-ajaran/{tahun_ajaran}/aktifkan', [TahunAjaranController::class, 'aktifkan'])->name('tahun-ajaran.aktifkan');
                Route::post('/tahun-ajaran/{tahun_ajaran}/pindah-genap', [TahunAjaranController::class, 'pindahKeSemesterGenap'])->name('tahun-ajaran.pindah-genap');
                Route::post('/tahun-ajaran/{tahun_ajaran}/akhiri-genap', [TahunAjaranController::class, 'akhiriSemesterGenap'])->name('tahun-ajaran.akhiri-genap');
                Route::post('/tahun-ajaran/{tahun_ajaran}/tutup', [TahunAjaranController::class, 'tutup'])->name('tahun-ajaran.tutup');
                Route::post('/tahun-ajaran/{tahun_ajaran}/kembalikan-ganjil', [TahunAjaranController::class, 'kembalikanKeGanjil'])->name('tahun-ajaran.kembalikan-ganjil');
                Route::post('/tahun-ajaran/{tahun_ajaran}/buka-kembali-genap', [TahunAjaranController::class, 'bukaKembaliGenap'])->name('tahun-ajaran.buka-kembali-genap');
                Route::post('/tahun-ajaran/{tahun_ajaran}/buka-kembali', [TahunAjaranController::class, 'bukaKembali'])->name('tahun-ajaran.buka-kembali');

                Route::view('/jurusan', 'admin-cabang.placeholder', [
                    'judul' => 'Jurusan',
                    'deskripsi' => 'Kelola data jurusan atau program keahlian di cabang sekolah.',
                ])->name('jurusan');

                Route::view('/kelas', 'admin-cabang.placeholder', [
                    'judul' => 'Kelas',
                    'deskripsi' => 'Kelola data kelas per jurusan dan tingkat.',
                ])->name('kelas');

                Route::view('/mata-pelajaran', 'admin-cabang.placeholder', [
                    'judul' => 'Mata Pelajaran',
                    'deskripsi' => 'Kelola daftar mata pelajaran yang diajarkan di cabang sekolah.',
                ])->name('mata-pelajaran');

                Route::view('/ekstrakurikuler', 'admin-cabang.placeholder', [
                    'judul' => 'Ekstrakurikuler',
                    'deskripsi' => 'Kelola kegiatan ekstrakurikuler yang tersedia di cabang sekolah.',
                ])->name('ekstrakurikuler');

                Route::view('/penugasan-mengajar', 'admin-cabang.placeholder', [
                    'judul' => 'Penugasan Mengajar',
                    'deskripsi' => 'Atur penugasan guru ke mata pelajaran dan kelas.',
                ])->name('penugasan-mengajar');
            });

            Route::prefix('master-data')->name('master-data.')->group(function () {
                Route::view('/guru', 'admin-cabang.placeholder', [
                    'judul' => 'Guru',
                    'deskripsi' => 'Kelola data guru di cabang sekolah Anda.',
                ])->name('guru');

                Route::view('/pembina', 'admin-cabang.placeholder', [
                    'judul' => 'Pembina Ekstrakurikuler',
                    'deskripsi' => 'Kelola data pembina ekstrakurikuler di cabang sekolah Anda.',
                ])->name('pembina');

                Route::view('/siswa', 'admin-cabang.placeholder', [
                    'judul' => 'Siswa',
                    'deskripsi' => 'Kelola data siswa di cabang sekolah Anda.',
                ])->name('siswa');
            });
        });

    Route::view('/guru', 'dashboard.guru')
        ->middleware('role:guru')
        ->name('guru.dashboard');

    Route::view('/pembina', 'dashboard.pembina')
        ->middleware('role:pembina_ekstrakurikuler')
        ->name('pembina.dashboard');

    Route::middleware('role:guru,pembina_ekstrakurikuler')->group(function () {
        Route::get('/arsip/tahun-ajaran', [ArsipTahunAjaranController::class, 'index'])->name('arsip.tahun-ajaran.index');
        Route::get('/arsip/tahun-ajaran/{tahun_ajaran}', [ArsipTahunAjaranController::class, 'show'])->name('arsip.tahun-ajaran.show');
    });
});
