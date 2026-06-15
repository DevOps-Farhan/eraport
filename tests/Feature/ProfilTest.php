<?php

namespace Tests\Feature;

use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfilTest extends TestCase
{
    use RefreshDatabase;

    public function test_pengguna_dapat_mengakses_halaman_profil(): void
    {
        $user = User::factory()->guru()->create();

        $response = $this->actingAs($user)->get(route('profil.edit'));

        $response->assertOk();
        $response->assertSee('Akun Profil');
        $response->assertSee($user->username);
        $response->assertSee('Informasi Profil');
        $response->assertSee('Ubah Password');
    }

    public function test_pengguna_dapat_memperbarui_profil(): void
    {
        $user = User::factory()->guru()->create([
            'nama' => 'Nama Lama',
            'email' => 'lama@test.com',
        ]);

        $response = $this->actingAs($user)->put(route('profil.update'), [
            'nama' => 'Nama Baru',
            'email' => 'baru@test.com',
        ]);

        $response->assertRedirect(route('profil.edit'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('pengguna', [
            'id' => $user->id,
            'nama' => 'Nama Baru',
            'email' => 'baru@test.com',
        ]);
    }

    public function test_pengguna_dapat_memperbarui_password(): void
    {
        $user = User::factory()->guru()->create([
            'password' => Hash::make('password-lama'),
        ]);

        $response = $this->actingAs($user)->put(route('profil.password.update'), [
            'password_lama' => 'password-lama',
            'password' => 'password-baru',
            'password_confirmation' => 'password-baru',
        ]);

        $response->assertRedirect(route('profil.edit'));
        $response->assertSessionHas('sukses');

        $user->refresh();
        $this->assertTrue(Hash::check('password-baru', $user->password));
    }

    public function test_password_lama_harus_benar(): void
    {
        $user = User::factory()->guru()->create([
            'password' => Hash::make('password-lama'),
        ]);

        $response = $this->from(route('profil.edit'))->actingAs($user)->put(route('profil.password.update'), [
            'password_lama' => 'salah',
            'password' => 'password-baru',
            'password_confirmation' => 'password-baru',
        ]);

        $response->assertRedirect(route('profil.edit'));
        $response->assertSessionHasErrors('password_lama');
    }

    public function test_tamu_tidak_dapat_akses_profil(): void
    {
        $response = $this->get(route('profil.edit'));

        $response->assertRedirect(route('login'));
    }

    public function test_admin_pusat_dapat_mengakses_profil(): void
    {
        $admin = User::factory()->adminPusat()->create();

        $response = $this->actingAs($admin)->get(route('profil.edit'));

        $response->assertOk();
        $response->assertSee('Admin Pusat');
    }

    public function test_profil_menampilkan_cabang_sekolah(): void
    {
        $cabang = CabangSekolah::factory()->create(['nama' => 'SMK Test']);
        $user = User::factory()->guru()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($user)->get(route('profil.edit'));

        $response->assertSee('SMK Test');
    }
}
