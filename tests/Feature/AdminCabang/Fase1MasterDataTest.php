<?php

namespace Tests\Feature\AdminCabang;

use App\Enums\JenisKelamin;
use App\Enums\KelompokMataPelajaran;
use App\Enums\PeranPengguna;
use App\Enums\TingkatKelas;
use App\Models\CabangSekolah;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Fase1MasterDataTest extends TestCase
{
    use RefreshDatabase;

    private function adminCabang(CabangSekolah $cabang): User
    {
        return User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);
    }

    public function test_admin_cabang_dapat_mengelola_jurusan(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabang);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.jurusan.store'), [
            'kode' => 'RPL',
            'nama' => 'Rekayasa Perangkat Lunak',
            'aktif' => true,
        ]);

        $response->assertRedirect(route('admin-cabang.data-akademik.jurusan.index'));
        $this->assertDatabaseHas('jurusan', [
            'cabang_sekolah_id' => $cabang->id,
            'kode' => 'RPL',
        ]);
    }

    public function test_admin_cabang_dapat_mengelola_mata_pelajaran(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabang);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.mata-pelajaran.store'), [
            'kode' => 'MTK',
            'nama' => 'Matematika',
            'kelompok' => KelompokMataPelajaran::Umum->value,
        ]);

        $response->assertRedirect(route('admin-cabang.data-akademik.mata-pelajaran.index'));
        $this->assertDatabaseHas('mata_pelajaran', [
            'cabang_sekolah_id' => $cabang->id,
            'kode' => 'MTK',
        ]);
    }

    public function test_admin_cabang_dapat_mengelola_kelas_tahun_ajaran_aktif(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabang);
        $tahunAjaran = TahunAjaran::factory()->aktif()->create(['cabang_sekolah_id' => $cabang->id]);
        $jurusan = Jurusan::factory()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.kelas.store'), [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jurusan_id' => $jurusan->id,
            'tingkat' => TingkatKelas::X->value,
            'nama' => 'X RPL 1',
        ]);

        $response->assertRedirect(route('admin-cabang.data-akademik.kelas.index'));
        $this->assertDatabaseHas('kelas', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'nama' => 'X RPL 1',
        ]);
    }

    public function test_tidak_bisa_tambah_kelas_tanpa_tahun_ajaran_aktif(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabang);
        $jurusan = Jurusan::factory()->create(['cabang_sekolah_id' => $cabang->id]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.kelas.store'), [
            'tahun_ajaran_id' => 1,
            'jurusan_id' => $jurusan->id,
            'tingkat' => TingkatKelas::X->value,
            'nama' => 'X RPL 1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
        $this->assertDatabaseCount('kelas', 0);
    }

    public function test_admin_cabang_dapat_mengelola_siswa_dan_rombel(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabang);
        $tahunAjaran = TahunAjaran::factory()->aktif()->create(['cabang_sekolah_id' => $cabang->id]);
        $jurusan = Jurusan::factory()->create(['cabang_sekolah_id' => $cabang->id]);
        $kelas = Kelas::factory()->create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jurusan_id' => $jurusan->id,
        ]);

        $store = $this->actingAs($admin)->post(route('admin-cabang.master-data.siswa.store'), [
            'nis' => '2026001',
            'nama' => 'Budi Santoso',
            'jenis_kelamin' => JenisKelamin::LakiLaki->value,
        ]);
        $store->assertRedirect(route('admin-cabang.master-data.siswa.index'));

        $siswa = Siswa::query()->where('nis', '2026001')->firstOrFail();

        $rombel = $this->actingAs($admin)->post(route('admin-cabang.master-data.siswa.rombel', $siswa), [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'kelas_id' => $kelas->id,
        ]);
        $rombel->assertRedirect(route('admin-cabang.master-data.siswa.index'));

        $this->assertDatabaseHas('rombel_siswa', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'siswa_id' => $siswa->id,
            'kelas_id' => $kelas->id,
        ]);
    }

    public function test_admin_cabang_dapat_mengelola_penugasan_mengajar(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabang);
        $tahunAjaran = TahunAjaran::factory()->aktif()->create(['cabang_sekolah_id' => $cabang->id]);
        $guru = User::factory()->guru()->create(['cabang_sekolah_id' => $cabang->id]);
        $mapel = MataPelajaran::factory()->create(['cabang_sekolah_id' => $cabang->id]);
        $jurusan = Jurusan::factory()->create(['cabang_sekolah_id' => $cabang->id]);
        $kelas = Kelas::factory()->create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jurusan_id' => $jurusan->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.penugasan-mengajar.store'), [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'guru_id' => $guru->id,
            'mata_pelajaran_id' => $mapel->id,
            'kelas_id' => $kelas->id,
        ]);

        $response->assertRedirect(route('admin-cabang.data-akademik.penugasan-mengajar.index'));
        $this->assertDatabaseHas('penugasan_mengajar', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'guru_id' => $guru->id,
            'mata_pelajaran_id' => $mapel->id,
            'kelas_id' => $kelas->id,
        ]);
    }

    public function test_jurusan_cabang_lain_tidak_dapat_diakses(): void
    {
        $cabangA = CabangSekolah::factory()->create();
        $cabangB = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabangA);
        $jurusan = Jurusan::factory()->create(['cabang_sekolah_id' => $cabangB->id]);

        $response = $this->actingAs($admin)->put(route('admin-cabang.data-akademik.jurusan.update', $jurusan), [
            'kode' => 'HACK',
            'nama' => 'Hacked',
        ]);

        $response->assertNotFound();
    }
}
