<?php

namespace Tests\Feature\AdminPusat;

use App\Enums\PeranPengguna;
use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenggunaTest extends TestCase
{
    use RefreshDatabase;

    public function test_halaman_guru_menampilkan_daftar_guru(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create(['nama' => 'Cabang Test']);
        User::factory()->guru()->create([
            'username' => 'guru.alpha',
            'nama' => 'Guru Alpha',
            'cabang_sekolah_id' => $cabang->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin-pusat.pengguna.guru'));

        $response->assertOk();
        $response->assertSee('Kelola Guru');
        $response->assertSee('guru.alpha');
        $response->assertSee('Guru Alpha');
        $response->assertSee('Cabang Test');
        $response->assertSee('Tambah Guru');
    }

    public function test_pencarian_guru_berdasarkan_nama(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();
        User::factory()->guru()->create([
            'username' => 'guru.satu',
            'nama' => 'Andi Wijaya',
            'cabang_sekolah_id' => $cabang->id,
        ]);
        User::factory()->guru()->create([
            'username' => 'guru.dua',
            'nama' => 'Budi Santoso',
            'cabang_sekolah_id' => $cabang->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin-pusat.pengguna.guru', ['cari' => 'Andi']));

        $response->assertOk();
        $response->assertSee('Andi Wijaya');
        $response->assertDontSee('Budi Santoso');
    }

    public function test_halaman_admin_cabang_menampilkan_daftar_admin_cabang(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create(['nama' => 'Cabang Jakarta']);
        User::factory()->adminCabang()->create([
            'username' => 'admin.jakarta',
            'nama' => 'Admin Jakarta',
            'cabang_sekolah_id' => $cabang->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin-pusat.pengguna.admin-cabang'));

        $response->assertOk();
        $response->assertSee('Kelola Admin Cabang');
        $response->assertSee('admin.jakarta');
        $response->assertSee('Admin Jakarta');
        $response->assertSee('Cabang Jakarta');
        $response->assertSee('Tambah Admin Cabang');
    }

    public function test_admin_pusat_dapat_menambah_admin_cabang(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin-pusat.pengguna.admin-cabang.store'), [
            'username' => 'admin.baru',
            'nama' => 'Admin Baru',
            'email' => 'admin.baru@test.com',
            'password' => 'password123',
            'cabang_sekolah_id' => $cabang->id,
            'aktif' => '1',
        ]);

        $response->assertRedirect(route('admin-pusat.pengguna.admin-cabang'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('pengguna', [
            'username' => 'admin.baru',
            'peran' => PeranPengguna::AdminCabang->value,
            'cabang_sekolah_id' => $cabang->id,
        ]);
    }

    public function test_halaman_pembina_menampilkan_daftar_pembina(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();
        User::factory()->pembinaEkstrakurikuler()->create([
            'username' => 'pembina.pramuka',
            'nama' => 'Siti Aminah',
            'cabang_sekolah_id' => $cabang->id,
        ]);

        $response = $this->actingAs($admin)->get(route('admin-pusat.pengguna.pembina'));

        $response->assertOk();
        $response->assertSee('Kelola Pembina Ekstrakurikuler');
        $response->assertSee('pembina.pramuka');
        $response->assertSee('Siti Aminah');
    }

    public function test_admin_pusat_dapat_menambah_guru(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin-pusat.pengguna.guru.store'), [
            'username' => 'guru.baru',
            'nama' => 'Guru Baru',
            'email' => 'guru.baru@test.com',
            'password' => 'password123',
            'cabang_sekolah_id' => $cabang->id,
            'aktif' => '1',
        ]);

        $response->assertRedirect(route('admin-pusat.pengguna.guru'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('pengguna', [
            'username' => 'guru.baru',
            'peran' => PeranPengguna::Guru->value,
            'cabang_sekolah_id' => $cabang->id,
        ]);
    }

    public function test_admin_pusat_dapat_menambah_pembina(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();

        $response = $this->actingAs($admin)->post(route('admin-pusat.pengguna.pembina.store'), [
            'username' => 'pembina.baru',
            'nama' => 'Pembina Baru',
            'password' => 'password123',
            'cabang_sekolah_id' => $cabang->id,
            'aktif' => '1',
        ]);

        $response->assertRedirect(route('admin-pusat.pengguna.pembina'));
        $this->assertDatabaseHas('pengguna', [
            'username' => 'pembina.baru',
            'peran' => PeranPengguna::PembinaEkstrakurikuler->value,
        ]);
    }

    public function test_admin_pusat_dapat_memperbarui_guru(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();
        $guru = User::factory()->guru()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->put(route('admin-pusat.pengguna.guru.update', $guru), [
            'username' => $guru->username,
            'nama' => 'Nama Diperbarui',
            'email' => 'baru@test.com',
            'cabang_sekolah_id' => $cabang->id,
            'aktif' => '1',
        ]);

        $response->assertRedirect(route('admin-pusat.pengguna.guru'));
        $this->assertDatabaseHas('pengguna', [
            'id' => $guru->id,
            'nama' => 'Nama Diperbarui',
            'email' => 'baru@test.com',
        ]);
    }

    public function test_admin_pusat_dapat_menghapus_guru(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();
        $guru = User::factory()->guru()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->delete(route('admin-pusat.pengguna.guru.destroy', $guru));

        $response->assertRedirect(route('admin-pusat.pengguna.guru'));
        $response->assertSessionHas('sukses');
        $this->assertDatabaseMissing('pengguna', ['id' => $guru->id]);
    }

    public function test_tidak_dapat_mengubah_guru_melalui_route_pembina(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();
        $guru = User::factory()->guru()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->put(route('admin-pusat.pengguna.pembina.update', $guru), [
            'username' => $guru->username,
            'nama' => 'Coba Ubah',
            'cabang_sekolah_id' => $cabang->id,
            'aktif' => '1',
        ]);

        $response->assertNotFound();
    }

    public function test_guru_tidak_dapat_akses_crud_pengguna(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $guru = User::factory()->guru()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($guru)->post(route('admin-pusat.pengguna.guru.store'), [
            'username' => 'guru.hacker',
            'nama' => 'Hacker',
            'password' => 'password123',
            'cabang_sekolah_id' => $cabang->id,
        ]);

        $response->assertForbidden();
    }
}
