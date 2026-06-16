<?php

namespace Tests\Feature\Guru;

use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_menampilkan_menu_utama(): void
    {
        $cabang = CabangSekolah::factory()->create(['nama' => 'SMK Alpha']);
        $guru = User::factory()->guru()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($guru)->get(route('guru.dashboard'));

        $response->assertOk();
        $response->assertSee('Dashboard');
        $response->assertSee('Kelola Nilai');
        $response->assertSee('Wali Kelas');
        $response->assertSee('Profil Akun');
        $response->assertSee('SMK Alpha');
    }

    public function test_sidebar_menampilkan_submenu_kelola_nilai(): void
    {
        $guru = User::factory()->guru()->create();

        $response = $this->actingAs($guru)->get(route('guru.kelola-nilai.tujuan-pembelajaran'));

        $response->assertOk();
        $response->assertSee('Tujuan Pembelajaran');
        $response->assertSee('Input Nilai Tujuan Pembelajaran');
        $response->assertSee('Nilai Ujian');
    }

    public function test_sidebar_menampilkan_submenu_wali_kelas(): void
    {
        $guru = User::factory()->guru()->create();

        $response = $this->actingAs($guru)->get(route('guru.wali-kelas.kelas'));

        $response->assertOk();
        $response->assertSee('Kelola Data Kelas');
        $response->assertSee('Rekap Absensi & Catatan');
        $response->assertSee('Leger & Cetak Raport');
    }

    public function test_halaman_placeholder_dapat_diakses(): void
    {
        $guru = User::factory()->guru()->create();

        $response = $this->actingAs($guru)->get(route('guru.kelola-nilai.nilai-ujian'));

        $response->assertOk();
        $response->assertSee('Nilai Ujian');
        $response->assertSee('Halaman dalam pengembangan');
    }

    public function test_guru_nonaktif_tidak_dapat_akses_menu(): void
    {
        $guru = User::factory()->guru()->inactive()->create();

        $response = $this->actingAs($guru)->get(route('guru.kelola-nilai.tujuan-pembelajaran'));

        $response->assertRedirect(route('login'));
    }
}
