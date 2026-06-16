<?php

namespace Tests\Feature;

use App\Enums\StatusTahunAjaran;
use App\Models\CabangSekolah;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArsipTahunAjaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_guru_dapat_melihat_arsip_tahun_ajaran(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $guru = User::factory()->guru()->create(['cabang_sekolah_id' => $cabang->id]);

        TahunAjaran::factory()->create([
            'cabang_sekolah_id' => $cabang->id,
            'nama' => '2024/2025',
            'status' => StatusTahunAjaran::Ditutup,
            'tanggal_selesai' => '2025-06-30',
        ]);

        $response = $this->actingAs($guru)->get(route('arsip.tahun-ajaran.index'));

        $response->assertOk();
        $response->assertSee('Arsip Tahun Ajaran');
        $response->assertSee('2024/2025');
        $response->assertSee('Lihat Detail');
    }

    public function test_pembina_dapat_melihat_detail_arsip(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $pembina = User::factory()->pembinaEkstrakurikuler()->create(['cabang_sekolah_id' => $cabang->id]);
        $tahunAjaran = TahunAjaran::factory()->create([
            'cabang_sekolah_id' => $cabang->id,
            'nama' => '2023/2024',
            'status' => StatusTahunAjaran::Ditutup,
            'tanggal_selesai' => '2024-06-30',
        ]);

        $response = $this->actingAs($pembina)->get(route('arsip.tahun-ajaran.show', $tahunAjaran));

        $response->assertOk();
        $response->assertSee('2023/2024');
        $response->assertSee('hanya baca', false);
    }

    public function test_guru_tidak_dapat_melihat_arsip_cabang_lain(): void
    {
        $cabangA = CabangSekolah::factory()->create();
        $cabangB = CabangSekolah::factory()->create();
        $guru = User::factory()->guru()->create(['cabang_sekolah_id' => $cabangA->id]);
        $tahunAjaran = TahunAjaran::factory()->create([
            'cabang_sekolah_id' => $cabangB->id,
            'status' => StatusTahunAjaran::Ditutup,
        ]);

        $response = $this->actingAs($guru)->get(route('arsip.tahun-ajaran.show', $tahunAjaran));

        $response->assertNotFound();
    }

    public function test_admin_cabang_tidak_dapat_akses_halaman_arsip(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->get(route('arsip.tahun-ajaran.index'));

        $response->assertForbidden();
    }
}
