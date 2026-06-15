<?php

namespace Tests\Feature\Auth;

use App\Enums\PeranPengguna;
use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_login_dapat_diakses(): void
    {
        $response = $this->get('/login');

        $response->assertOk();
        $response->assertSee('Masuk ke eRaport');
    }

    public function test_pengguna_dapat_login_dengan_kredensial_valid(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $user = User::factory()->guru()->create([
            'username' => 'guru.test',
            'cabang_sekolah_id' => $cabang->id,
        ]);

        $response = $this->post('/login', [
            'username' => 'guru.test',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('guru.dashboard'));
    }

    public function test_login_gagal_dengan_password_salah(): void
    {
        User::factory()->guru()->create([
            'username' => 'guru.test',
        ]);

        $response = $this->from('/login')->post('/login', [
            'username' => 'guru.test',
            'password' => 'salah',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
    }

    public function test_pengguna_nonaktif_tidak_dapat_login(): void
    {
        User::factory()->guru()->inactive()->create([
            'username' => 'guru.nonaktif',
        ]);

        $response = $this->from('/login')->post('/login', [
            'username' => 'guru.nonaktif',
            'password' => 'password',
        ]);

        $this->assertGuest();
        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('username');
        $response->assertSessionHasErrors([
            'username' => 'Akun Anda dinonaktifkan. Hubungi administrator.',
        ]);
    }

    public function test_pengguna_dapat_logout(): void
    {
        $user = User::factory()->adminPusat()->create([
            'username' => 'superadmin',
        ]);

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect(route('login'));
    }

    public function test_root_redirect_ke_login_jika_belum_auth(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_root_redirect_ke_dashboard_jika_sudah_auth(): void
    {
        $user = User::factory()->adminPusat()->create();

        $response = $this->actingAs($user)->get('/');

        $response->assertRedirect(route('admin-pusat.dashboard'));
    }

    public function test_admin_pusat_redirect_ke_dashboard_admin_pusat(): void
    {
        $user = User::factory()->adminPusat()->create([
            'username' => 'superadmin',
        ]);

        $response = $this->post('/login', [
            'username' => 'superadmin',
            'password' => 'password',
        ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('admin-pusat.dashboard'));
    }

    public function test_remember_me_menyimpan_token(): void
    {
        $user = User::factory()->guru()->create([
            'username' => 'guru.test',
            'remember_token' => null,
        ]);

        $response = $this->post('/login', [
            'username' => 'guru.test',
            'password' => 'password',
            'remember' => '1',
        ]);

        $response->assertRedirect(route('guru.dashboard'));
        $this->assertNotNull($user->fresh()->remember_token);
    }
}
