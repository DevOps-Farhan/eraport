<?php

namespace Tests\Feature\AdminCabang;

use App\Enums\PeranPengguna;
use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenggunaCabangTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cabang_dapat_menambah_guru(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.master-data.guru.store'), [
            'username' => 'guru.baru',
            'nama' => 'Guru Baru',
            'email' => 'guru.baru@eraport.test',
            'password' => 'password123',
            'aktif' => true,
        ]);

        $response->assertRedirect(route('admin-cabang.master-data.guru.index'));
        $this->assertDatabaseHas('pengguna', [
            'username' => 'guru.baru',
            'peran' => PeranPengguna::Guru->value,
            'cabang_sekolah_id' => $cabang->id,
        ]);
    }

    public function test_admin_cabang_dapat_menambah_pembina(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.master-data.pembina.store'), [
            'username' => 'pembina.baru',
            'nama' => 'Pembina Baru',
            'password' => 'password123',
        ]);

        $response->assertRedirect(route('admin-cabang.master-data.pembina.index'));
        $this->assertDatabaseHas('pengguna', [
            'username' => 'pembina.baru',
            'peran' => PeranPengguna::PembinaEkstrakurikuler->value,
            'cabang_sekolah_id' => $cabang->id,
        ]);
    }

    public function test_admin_cabang_tidak_dapat_mengubah_guru_cabang_lain(): void
    {
        $cabangA = CabangSekolah::factory()->create();
        $cabangB = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabangA->id]);
        $guru = User::factory()->guru()->create(['cabang_sekolah_id' => $cabangB->id]);

        $response = $this->actingAs($admin)->put(route('admin-cabang.master-data.guru.update', $guru), [
            'username' => 'hacked',
            'nama' => 'Hacked',
        ]);

        $response->assertNotFound();
    }
}
