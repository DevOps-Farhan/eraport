<?php

namespace Tests\Feature\AdminPusat;

use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_menampilkan_jumlah_cabang_dan_pengguna(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();
        User::factory()->guru()->count(2)->create(['cabang_sekolah_id' => $cabang->id]);
        User::factory()->pembinaEkstrakurikuler()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->get(route('admin-pusat.dashboard'));

        $response->assertOk();
        $response->assertSee('Selamat datang kembali');
        $response->assertSee('Total Cabang Sekolah');
        $response->assertSee('1', false);
        $response->assertSee('Total Pengguna');
        $response->assertSee('3', false);
    }

    public function test_sidebar_terdapat_menu_utama(): void
    {
        $admin = User::factory()->adminPusat()->create();

        $response = $this->actingAs($admin)->get(route('admin-pusat.dashboard'));

        $response->assertSee('Dashboard');
        $response->assertSee('Kelola Semua Cabang');
        $response->assertSee('Kelola Pengguna');
        $response->assertSee('Admin Cabang');
        $response->assertSee('Guru');
        $response->assertSee('Pembina');
        $response->assertSee('Logout');
    }
}
