<?php

namespace Tests\Feature\AdminPusat;

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
}
