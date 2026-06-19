<?php

namespace App\Providers;

use App\Services\KonteksAkademik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Auth::guard('web')->setRememberDuration(60 * 24 * 30);

        View::composer(['layouts.admin-cabang', 'layouts.guru', 'layouts.pembina'], function ($view) {
            $user = auth()->user();

            if ($user?->cabang_sekolah_id && ($user->isAdminCabang() || $user->isGuru() || $user->isPembina())) {
                $view->with(
                    'tahunAjaranAktif',
                    app(KonteksAkademik::class)->tahunAjaranAktif($user->cabang_sekolah_id),
                );
            }
        });
    }
}
