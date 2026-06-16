<?php

namespace Tests\Feature\AdminCabang;

use App\Enums\AksiLogAkademik;
use App\Enums\JenisSemester;
use App\Enums\StatusSemester;
use App\Enums\StatusTahunAjaran;
use App\Models\CabangSekolah;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TahunAjaranTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_cabang_dapat_melihat_daftar_tahun_ajaran(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);

        TahunAjaran::factory()->create([
            'cabang_sekolah_id' => $cabang->id,
            'nama' => '2025/2026',
        ]);

        $response = $this->actingAs($admin)->get(route('admin-cabang.data-akademik.tahun-ajaran.index'));

        $response->assertOk();
        $response->assertSee('2025/2026');
        $response->assertSee('Tambah Tahun Ajaran');
        $response->assertSee('modal-konfirmasi-tahun-ajaran');
    }

    public function test_admin_cabang_dapat_menambah_tahun_ajaran(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.store'), [
            'nama' => '2026/2027',
            'tanggal_mulai' => '2026-07-01',
        ]);

        $response->assertRedirect(route('admin-cabang.data-akademik.tahun-ajaran.index'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('tahun_ajaran', [
            'cabang_sekolah_id' => $cabang->id,
            'nama' => '2026/2027',
            'status' => StatusTahunAjaran::Draft->value,
        ]);

        $this->assertDatabaseHas('log_akademik', [
            'cabang_sekolah_id' => $cabang->id,
            'pengguna_id' => $admin->id,
            'aksi' => AksiLogAkademik::TahunAjaranDibuat->value,
        ]);
    }

    public function test_admin_cabang_dapat_mengaktifkan_tahun_ajaran(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);
        $tahunAjaran = TahunAjaran::factory()->create([
            'cabang_sekolah_id' => $cabang->id,
            'status' => StatusTahunAjaran::Draft,
        ]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.aktifkan', $tahunAjaran));

        $response->assertRedirect(route('admin-cabang.data-akademik.tahun-ajaran.index'));
        $response->assertSessionHas('sukses');

        $tahunAjaran->refresh();
        $this->assertSame(StatusTahunAjaran::Aktif, $tahunAjaran->status);

        $this->assertDatabaseHas('semester', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jenis' => JenisSemester::Ganjil->value,
            'status' => StatusSemester::Berjalan->value,
        ]);

        $this->assertDatabaseHas('semester', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jenis' => JenisSemester::Genap->value,
            'status' => StatusSemester::BelumDimulai->value,
        ]);

        $this->assertDatabaseHas('log_akademik', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'aksi' => AksiLogAkademik::TahunAjaranDiaktifkan->value,
        ]);
    }

    public function test_tidak_bisa_aktifkan_dua_tahun_ajaran_bersamaan(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);

        TahunAjaran::factory()->aktif()->create(['cabang_sekolah_id' => $cabang->id]);

        $draft = TahunAjaran::factory()->create([
            'cabang_sekolah_id' => $cabang->id,
            'status' => StatusTahunAjaran::Draft,
        ]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.aktifkan', $draft));

        $response->assertRedirect(route('admin-cabang.data-akademik.tahun-ajaran.index'));
        $response->assertSessionHas('gagal');
    }

    public function test_admin_cabang_dapat_pindah_ke_semester_genap(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);
        $tahunAjaran = TahunAjaran::factory()->aktif()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.pindah-genap', $tahunAjaran));

        $response->assertRedirect(route('admin-cabang.data-akademik.tahun-ajaran.index'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('semester', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jenis' => JenisSemester::Ganjil->value,
            'status' => StatusSemester::Selesai->value,
        ]);

        $this->assertDatabaseHas('semester', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jenis' => JenisSemester::Genap->value,
            'status' => StatusSemester::Berjalan->value,
        ]);
    }

    public function test_admin_cabang_dapat_kembalikan_ke_semester_ganjil(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);
        $tahunAjaran = TahunAjaran::factory()->aktif()->create(['cabang_sekolah_id' => $cabang->id]);

        $tahunAjaran->semesterGanjil()?->update(['status' => StatusSemester::Selesai]);
        $tahunAjaran->semesterGenap()?->update(['status' => StatusSemester::Berjalan]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.kembalikan-ganjil', $tahunAjaran));

        $response->assertRedirect(route('admin-cabang.data-akademik.tahun-ajaran.index'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('semester', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jenis' => JenisSemester::Ganjil->value,
            'status' => StatusSemester::Berjalan->value,
        ]);

        $this->assertDatabaseHas('semester', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jenis' => JenisSemester::Genap->value,
            'status' => StatusSemester::BelumDimulai->value,
        ]);
    }

    public function test_admin_cabang_dapat_menutup_tahun_ajaran(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);
        $tahunAjaran = TahunAjaran::factory()->aktif()->create(['cabang_sekolah_id' => $cabang->id]);

        $tahunAjaran->semesterGanjil()?->update(['status' => StatusSemester::Selesai]);
        $tahunAjaran->semesterGenap()?->update(['status' => StatusSemester::Selesai]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.tutup', $tahunAjaran));

        $response->assertRedirect(route('admin-cabang.data-akademik.tahun-ajaran.index'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('tahun_ajaran', [
            'id' => $tahunAjaran->id,
            'status' => StatusTahunAjaran::Ditutup->value,
        ]);
    }

    public function test_admin_cabang_dapat_buka_kembali_tahun_ajaran_ditutup(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);
        $tahunAjaran = TahunAjaran::factory()->create([
            'cabang_sekolah_id' => $cabang->id,
            'status' => StatusTahunAjaran::Ditutup,
            'tanggal_selesai' => now()->toDateString(),
        ]);

        $tahunAjaran->semester()->create([
            'jenis' => JenisSemester::Ganjil,
            'status' => StatusSemester::Selesai,
        ]);
        $tahunAjaran->semester()->create([
            'jenis' => JenisSemester::Genap,
            'status' => StatusSemester::Selesai,
        ]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.buka-kembali', $tahunAjaran));

        $response->assertRedirect(route('admin-cabang.data-akademik.tahun-ajaran.index'));
        $response->assertSessionHas('sukses');

        $tahunAjaran->refresh();
        $this->assertSame(StatusTahunAjaran::Aktif, $tahunAjaran->status);
        $this->assertSame(StatusSemester::Berjalan, $tahunAjaran->semesterGenap()?->status);
    }

    public function test_admin_cabang_tidak_dapat_akses_tahun_ajaran_cabang_lain(): void
    {
        $cabangA = CabangSekolah::factory()->create();
        $cabangB = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabangA->id]);
        $tahunAjaran = TahunAjaran::factory()->create(['cabang_sekolah_id' => $cabangB->id]);

        $response = $this->actingAs($admin)->put(route('admin-cabang.data-akademik.tahun-ajaran.update', $tahunAjaran), [
            'nama' => 'Hacked',
            'tanggal_mulai' => '2026-07-01',
        ]);

        $response->assertNotFound();
    }

    public function test_dashboard_menampilkan_konteks_tahun_ajaran_aktif(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);
        TahunAjaran::factory()->aktif()->create([
            'cabang_sekolah_id' => $cabang->id,
            'nama' => '2025/2026',
        ]);

        $response = $this->actingAs($admin)->get(route('admin-cabang.dashboard'));

        $response->assertOk();
        $response->assertSee('Konteks Aktif');
        $response->assertSee('2025/2026');
        $response->assertSee('Semester Ganjil');
    }
}
