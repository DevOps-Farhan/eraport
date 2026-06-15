<?php

namespace Tests\Feature\AdminCabang;

use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SidebarTest extends TestCase
{
    use RefreshDatabase;

    public function test_sidebar_menampilkan_menu_data_akademik(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->get(route('admin-cabang.dashboard'));

        $response->assertOk();
        $response->assertSee('Data Akademik');
        $response->assertSee('Tahun Ajaran');
        $response->assertSee('Jurusan');
        $response->assertSee('Kelas');
        $response->assertSee('Mata Pelajaran');
        $response->assertSee('Ekstrakurikuler');
        $response->assertSee('Penugasan Mengajar');
    }

    public function test_sidebar_menampilkan_menu_master_data(): void
    {
        $admin = User::factory()->adminCabang()->create();

        $response = $this->actingAs($admin)->get(route('admin-cabang.dashboard'));

        $response->assertSee('Master Data');
        $response->assertSee('Pembina Ekstrakurikuler');
        $response->assertSee('Siswa');
    }

    public function test_halaman_placeholder_dapat_diakses(): void
    {
        $admin = User::factory()->adminCabang()->create();

        $response = $this->actingAs($admin)->get(route('admin-cabang.data-akademik.tahun-ajaran'));

        $response->assertOk();
        $response->assertSee('Tahun Ajaran');
        $response->assertSee('Halaman dalam pengembangan');
    }

    public function test_guru_tidak_dapat_akses_halaman_admin_cabang(): void
    {
        $guru = User::factory()->guru()->create();

        $response = $this->actingAs($guru)->get(route('admin-cabang.data-akademik.kelas'));

        $response->assertForbidden();
    }
}
