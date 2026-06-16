<?php

use App\Http\Controllers\AdminCabang\EkstrakurikulerController;
use App\Http\Controllers\AdminCabang\JurusanController;
use App\Http\Controllers\AdminCabang\KelasController;
use App\Http\Controllers\AdminCabang\MataPelajaranController;
use App\Http\Controllers\AdminCabang\PenugasanMengajarController;
use App\Http\Controllers\AdminCabang\PenggunaCabangController;
use App\Http\Controllers\AdminCabang\SiswaController;
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

                Route::get('/jurusan', [JurusanController::class, 'index'])->name('jurusan.index');
                Route::post('/jurusan', [JurusanController::class, 'store'])->name('jurusan.store');
                Route::put('/jurusan/{jurusan}', [JurusanController::class, 'update'])->name('jurusan.update');
                Route::delete('/jurusan/{jurusan}', [JurusanController::class, 'destroy'])->name('jurusan.destroy');

                Route::get('/kelas', [KelasController::class, 'index'])->name('kelas.index');
                Route::post('/kelas', [KelasController::class, 'store'])->name('kelas.store');
                Route::put('/kelas/{kelas}', [KelasController::class, 'update'])->name('kelas.update');
                Route::delete('/kelas/{kelas}', [KelasController::class, 'destroy'])->name('kelas.destroy');

                Route::get('/mata-pelajaran', [MataPelajaranController::class, 'index'])->name('mata-pelajaran.index');
                Route::post('/mata-pelajaran', [MataPelajaranController::class, 'store'])->name('mata-pelajaran.store');
                Route::put('/mata-pelajaran/{mata_pelajaran}', [MataPelajaranController::class, 'update'])->name('mata-pelajaran.update');
                Route::delete('/mata-pelajaran/{mata_pelajaran}', [MataPelajaranController::class, 'destroy'])->name('mata-pelajaran.destroy');

                Route::get('/ekstrakurikuler', [EkstrakurikulerController::class, 'index'])->name('ekstrakurikuler.index');
                Route::post('/ekstrakurikuler', [EkstrakurikulerController::class, 'store'])->name('ekstrakurikuler.store');
                Route::put('/ekstrakurikuler/{ekstrakurikuler}', [EkstrakurikulerController::class, 'update'])->name('ekstrakurikuler.update');
                Route::delete('/ekstrakurikuler/{ekstrakurikuler}', [EkstrakurikulerController::class, 'destroy'])->name('ekstrakurikuler.destroy');
                Route::post('/ekstrakurikuler/penugasan-pembina', [EkstrakurikulerController::class, 'storePenugasan'])->name('ekstrakurikuler.penugasan.store');
                Route::delete('/ekstrakurikuler/penugasan-pembina/{penugasan_pembina}', [EkstrakurikulerController::class, 'destroyPenugasan'])->name('ekstrakurikuler.penugasan.destroy');

                Route::get('/penugasan-mengajar', [PenugasanMengajarController::class, 'index'])->name('penugasan-mengajar.index');
                Route::post('/penugasan-mengajar', [PenugasanMengajarController::class, 'store'])->name('penugasan-mengajar.store');
                Route::delete('/penugasan-mengajar/{penugasan_mengajar}', [PenugasanMengajarController::class, 'destroy'])->name('penugasan-mengajar.destroy');
            });

            Route::prefix('master-data')->name('master-data.')->group(function () {
                Route::get('/guru', [PenggunaCabangController::class, 'guru'])->name('guru.index');
                Route::post('/guru', [PenggunaCabangController::class, 'store'])
                    ->defaults('tipe', 'guru')
                    ->name('guru.store');
                Route::put('/guru/{pengguna}', [PenggunaCabangController::class, 'update'])
                    ->defaults('tipe', 'guru')
                    ->name('guru.update');
                Route::delete('/guru/{pengguna}', [PenggunaCabangController::class, 'destroy'])
                    ->defaults('tipe', 'guru')
                    ->name('guru.destroy');

                Route::get('/pembina', [PenggunaCabangController::class, 'pembina'])->name('pembina.index');
                Route::post('/pembina', [PenggunaCabangController::class, 'store'])
                    ->defaults('tipe', 'pembina')
                    ->name('pembina.store');
                Route::put('/pembina/{pengguna}', [PenggunaCabangController::class, 'update'])
                    ->defaults('tipe', 'pembina')
                    ->name('pembina.update');
                Route::delete('/pembina/{pengguna}', [PenggunaCabangController::class, 'destroy'])
                    ->defaults('tipe', 'pembina')
                    ->name('pembina.destroy');

                Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
                Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');
                Route::put('/siswa/{siswa}', [SiswaController::class, 'update'])->name('siswa.update');
                Route::delete('/siswa/{siswa}', [SiswaController::class, 'destroy'])->name('siswa.destroy');
                Route::post('/siswa/{siswa}/rombel', [SiswaController::class, 'assignRombel'])->name('siswa.rombel');
            });
        });

    Route::prefix('guru')
        ->middleware('role:guru')
        ->name('guru.')
        ->group(function () {
            Route::view('/', 'guru.dashboard')->name('dashboard');

            Route::prefix('kelola-nilai')->name('kelola-nilai.')->group(function () {
                Route::view('/tujuan-pembelajaran', 'guru.placeholder', [
                    'judul' => 'Tujuan Pembelajaran',
                    'deskripsi' => 'Kelola tujuan pembelajaran untuk mata pelajaran yang Anda ampu.',
                ])->name('tujuan-pembelajaran');

                Route::view('/input-nilai-tp', 'guru.placeholder', [
                    'judul' => 'Input Nilai Tujuan Pembelajaran',
                    'deskripsi' => 'Input nilai capaian tujuan pembelajaran per siswa.',
                ])->name('input-nilai-tp');

                Route::view('/nilai-ujian', 'guru.placeholder', [
                    'judul' => 'Nilai Ujian',
                    'deskripsi' => 'Input dan kelola nilai ujian mata pelajaran.',
                ])->name('nilai-ujian');
            });

            Route::prefix('wali-kelas')->name('wali-kelas.')->group(function () {
                Route::view('/kelas', 'guru.placeholder', [
                    'judul' => 'Kelola Data Kelas sebagai Wali',
                    'deskripsi' => 'Kelola data siswa dan informasi kelas yang Anda wali.',
                ])->name('kelas');

                Route::view('/absensi-catatan', 'guru.placeholder', [
                    'judul' => 'Rekap Absensi dan Catatan',
                    'deskripsi' => 'Rekap kehadiran siswa dan catatan wali kelas.',
                ])->name('absensi-catatan');

                Route::view('/leger-raport', 'guru.placeholder', [
                    'judul' => 'Leger & Cetak Raport',
                    'deskripsi' => 'Lihat leger nilai dan cetak raport siswa.',
                ])->name('leger-raport');
            });
        });

    Route::view('/pembina', 'dashboard.pembina')
        ->middleware('role:pembina_ekstrakurikuler')
        ->name('pembina.dashboard');

    Route::middleware('role:guru,pembina_ekstrakurikuler')->group(function () {
        Route::get('/arsip/tahun-ajaran', [ArsipTahunAjaranController::class, 'index'])->name('arsip.tahun-ajaran.index');
        Route::get('/arsip/tahun-ajaran/{tahun_ajaran}', [ArsipTahunAjaranController::class, 'show'])->name('arsip.tahun-ajaran.show');
    });
});
