<?php

namespace App\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin-pusat.dashboard', [
            'jumlahCabang' => CabangSekolah::query()->count(),
            'jumlahPengguna' => User::query()->where('peran', '!=', 'admin_pusat')->count(),
        ]);
    }
}
