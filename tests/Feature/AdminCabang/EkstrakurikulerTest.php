<?php

namespace Tests\Feature\AdminCabang;

use App\Enums\PeranPengguna;
use App\Models\CabangSekolah;
use App\Models\Ekstrakurikuler;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EkstrakurikulerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cabang_dapat_mengelola_ekstrakurikuler(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.ekstrakurikuler.store'), [
            'kode' => 'PRA',
            'nama' => 'Pramuka',
            'aktif' => true,
        ]);

        $response->assertRedirect(route('admin-cabang.data-akademik.ekstrakurikuler.index'));
        $this->assertDatabaseHas('ekstrakurikuler', [
            'cabang_sekolah_id' => $cabang->id,
            'kode' => 'PRA',
        ]);
    }

    public function test_admin_cabang_dapat_menugaskan_pembina(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);
        $tahunAjaran = TahunAjaran::factory()->aktif()->create(['cabang_sekolah_id' => $cabang->id]);
        $ekstra = Ekstrakurikuler::factory()->create(['cabang_sekolah_id' => $cabang->id]);
        $pembina = User::factory()->pembinaEkstrakurikuler()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.ekstrakurikuler.penugasan.store'), [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'ekstrakurikuler_id' => $ekstra->id,
            'pembina_id' => $pembina->id,
        ]);

        $response->assertRedirect(route('admin-cabang.data-akademik.ekstrakurikuler.index'));
        $this->assertDatabaseHas('penugasan_pembina', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'ekstrakurikuler_id' => $ekstra->id,
            'pembina_id' => $pembina->id,
        ]);
    }
}
