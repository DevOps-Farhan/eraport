<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_tidak_dapat_akses_dashboard_admin_pusat(): void
    {
        $user = User::factory()->guru()->create();

        $response = $this->actingAs($user)->get(route('admin-pusat.dashboard'));

        $response->assertForbidden();
    }

    public function test_admin_pusat_dapat_akses_dashboard_admin_pusat(): void
    {
        $user = User::factory()->adminPusat()->create();

        $response = $this->actingAs($user)->get(route('admin-pusat.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee('Selamat datang kembali');
    }

    public function test_admin_cabang_dapat_akses_dashboard_admin_cabang(): void
    {
        $user = User::factory()->adminCabang()->create();

        $response = $this->actingAs($user)->get(route('admin-cabang.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee('Data Akademik');
        $response->assertSee('Master Data');
        $response->assertSee('Profil Akun');
    }

    public function test_pembina_dapat_akses_dashboard_pembina(): void
    {
        $user = User::factory()->pembinaEkstrakurikuler()->create();

        $response = $this->actingAs($user)->get(route('pembina.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard Pembina Ekstrakurikuler');
    }

    public function test_tamu_redirect_ke_login_saat_akses_dashboard(): void
    {
        $response = $this->get(route('guru.dashboard'));

        $response->assertRedirect(route('login'));
    }

    public function test_pengguna_nonaktif_dikeluarkan_saat_akses_halaman(): void
    {
        $user = User::factory()->guru()->create(['aktif' => true]);

        $this->actingAs($user)->get(route('guru.dashboard'))->assertOk();

        $user->update(['aktif' => false]);

        $response = $this->actingAs($user->fresh())->get(route('guru.dashboard'));

        $response->assertRedirect(route('login'));
        $this->assertGuest();
    }
}
