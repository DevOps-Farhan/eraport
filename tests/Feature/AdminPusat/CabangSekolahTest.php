<?php

namespace Tests\Feature\AdminPusat;

use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CabangSekolahTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pusat_dapat_menambah_cabang(): void
    {
        $admin = User::factory()->adminPusat()->create();

        $response = $this->actingAs($admin)->post(route('admin-pusat.cabang-sekolah.store'), [
            'kode' => 'CS-BDG-01',
            'nama' => 'SMK Negeri 2 Bandung',
            'kepala_sekolah' => 'Bapak Budi',
            'alamat' => 'Jl. Merdeka No. 10',
            'telepon' => '022-123456',
            'aktif' => '1',
        ]);

        $response->assertRedirect(route('admin-pusat.cabang-sekolah.index'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('cabang_sekolah', [
            'kode' => 'CS-BDG-01',
            'kepala_sekolah' => 'Bapak Budi',
        ]);
    }

    public function test_admin_pusat_dapat_memperbarui_cabang(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();

        $response = $this->actingAs($admin)->put(route('admin-pusat.cabang-sekolah.update', $cabang), [
            'kode' => $cabang->kode,
            'nama' => 'Nama Baru',
            'kepala_sekolah' => 'Kepala Baru',
            'alamat' => 'Alamat Baru',
            'telepon' => '021-999',
            'aktif' => '1',
        ]);

        $response->assertRedirect(route('admin-pusat.cabang-sekolah.index'));
        $this->assertDatabaseHas('cabang_sekolah', [
            'id' => $cabang->id,
            'nama' => 'Nama Baru',
            'kepala_sekolah' => 'Kepala Baru',
        ]);
    }

    public function test_cabang_dengan_pengguna_tidak_dapat_dihapus(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();
        User::factory()->guru()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->delete(route('admin-pusat.cabang-sekolah.destroy', $cabang));

        $response->assertRedirect(route('admin-pusat.cabang-sekolah.index'));
        $response->assertSessionHas('gagal');
        $this->assertDatabaseHas('cabang_sekolah', ['id' => $cabang->id]);
    }

    public function test_admin_pusat_dapat_menghapus_cabang_tanpa_pengguna(): void
    {
        $admin = User::factory()->adminPusat()->create();
        $cabang = CabangSekolah::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin-pusat.cabang-sekolah.destroy', $cabang));

        $response->assertRedirect(route('admin-pusat.cabang-sekolah.index'));
        $response->assertSessionHas('sukses');
        $this->assertDatabaseMissing('cabang_sekolah', ['id' => $cabang->id]);
    }

    public function test_guru_tidak_dapat_akses_kelola_cabang(): void
    {
        $guru = User::factory()->guru()->create();

        $response = $this->actingAs($guru)->get(route('admin-pusat.cabang-sekolah.index'));

        $response->assertForbidden();
    }
}
