<?php

namespace Tests\Feature\AdminCabang;

use App\Enums\AksiLogAkademik;
use App\Enums\JenisSemester;
use App\Enums\StatusSemester;
use App\Enums\StatusTahunAjaran;
use App\Models\AbsensiSiswa;
use App\Models\CabangSekolah;
use App\Models\NilaiTujuanPembelajaran;
use App\Models\NilaiUjian;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Feature\Guru\MenyiapkanDataGuru;
use Tests\TestCase;

class TahunAjaranTest extends TestCase
{
    use MenyiapkanDataGuru;
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

    public function test_kembalikan_ke_ganjil_dengan_nilai_tp_genap_menjaga_data(): void
    {
        $data = $this->siapkanDataGuru();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $data['cabang']->id]);
        $tahunAjaran = $data['tahunAjaran'];
        $genap = $tahunAjaran->semesterGenap();

        $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.pindah-genap', $tahunAjaran));

        $nilai = NilaiTujuanPembelajaran::factory()->create([
            'semester_id' => $genap->id,
            'siswa_id' => $data['siswa']->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.kembalikan-ganjil', $tahunAjaran));

        $response->assertRedirect(route('admin-cabang.data-akademik.tahun-ajaran.index'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('semester', [
            'id' => $genap->id,
            'status' => StatusSemester::Ditangguhkan->value,
        ]);
        $this->assertDatabaseHas('semester', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jenis' => JenisSemester::Ganjil->value,
            'status' => StatusSemester::Berjalan->value,
        ]);
        $this->assertDatabaseHas('nilai_tujuan_pembelajaran', ['id' => $nilai->id]);
    }

    public function test_kembalikan_ke_ganjil_dengan_absensi_genap_menjaga_data(): void
    {
        $data = $this->siapkanDataGuru();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $data['cabang']->id]);
        $tahunAjaran = $data['tahunAjaran'];
        $genap = $tahunAjaran->semesterGenap();

        $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.pindah-genap', $tahunAjaran));

        $absensi = AbsensiSiswa::factory()->create([
            'semester_id' => $genap->id,
            'siswa_id' => $data['siswa']->id,
        ]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.kembalikan-ganjil', $tahunAjaran));

        $response->assertSessionHas('sukses');
        $this->assertDatabaseHas('absensi_siswa', ['id' => $absensi->id]);
        $this->assertDatabaseHas('semester', [
            'id' => $genap->id,
            'status' => StatusSemester::Ditangguhkan->value,
        ]);
    }

    public function test_lanjutkan_genap_setelah_ditangguhkan(): void
    {
        $data = $this->siapkanDataGuru();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $data['cabang']->id]);
        $tahunAjaran = $data['tahunAjaran'];
        $genap = $tahunAjaran->semesterGenap();

        $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.pindah-genap', $tahunAjaran));

        $nilai = NilaiTujuanPembelajaran::factory()->create([
            'semester_id' => $genap->id,
            'siswa_id' => $data['siswa']->id,
        ]);

        $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.kembalikan-ganjil', $tahunAjaran));

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.pindah-genap', $tahunAjaran));

        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('semester', [
            'id' => $genap->id,
            'status' => StatusSemester::Berjalan->value,
        ]);
        $this->assertDatabaseHas('semester', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jenis' => JenisSemester::Ganjil->value,
            'status' => StatusSemester::Selesai->value,
        ]);
        $this->assertDatabaseHas('nilai_tujuan_pembelajaran', ['id' => $nilai->id]);
        $this->assertDatabaseHas('log_akademik', [
            'tahun_ajaran_id' => $tahunAjaran->id,
            'aksi' => AksiLogAkademik::SemesterGenapDilanjutkan->value,
        ]);
    }

    public function test_buka_kembali_genap_dengan_data(): void
    {
        $data = $this->siapkanDataGuru();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $data['cabang']->id]);
        $tahunAjaran = $data['tahunAjaran'];
        $genap = $tahunAjaran->semesterGenap();

        $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.pindah-genap', $tahunAjaran));

        NilaiUjian::factory()->create([
            'semester_id' => $genap->id,
            'siswa_id' => $data['siswa']->id,
            'penugasan_mengajar_id' => $data['penugasan']->id,
        ]);

        $tahunAjaran->semesterGenap()?->update(['status' => StatusSemester::Selesai]);

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.buka-kembali-genap', $tahunAjaran));

        $response->assertSessionHas('sukses');
        $this->assertSame(StatusSemester::Berjalan, $tahunAjaran->fresh()->semesterGenap()?->status);
    }

    public function test_tutup_ta_gagal_saat_genap_ditangguhkan(): void
    {
        $data = $this->siapkanDataGuru();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $data['cabang']->id]);
        $tahunAjaran = $data['tahunAjaran'];
        $genap = $tahunAjaran->semesterGenap();

        $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.pindah-genap', $tahunAjaran));

        NilaiTujuanPembelajaran::factory()->create([
            'semester_id' => $genap->id,
            'siswa_id' => $data['siswa']->id,
        ]);

        $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.kembalikan-ganjil', $tahunAjaran));

        $response = $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.tutup', $tahunAjaran));

        $response->assertSessionHas('gagal');
        $this->assertSame(StatusTahunAjaran::Aktif, $tahunAjaran->fresh()->status);
    }

    public function test_guru_dapat_simpan_nilai_ganjil_setelah_rollback(): void
    {
        $data = $this->siapkanDataGuru();
        $admin = User::factory()->adminCabang()->create(['cabang_sekolah_id' => $data['cabang']->id]);
        $tahunAjaran = $data['tahunAjaran'];
        $ganjil = $tahunAjaran->semesterGanjil();
        $genap = $tahunAjaran->semesterGenap();

        $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.pindah-genap', $tahunAjaran));

        NilaiTujuanPembelajaran::factory()->create([
            'semester_id' => $genap->id,
            'siswa_id' => $data['siswa']->id,
        ]);

        $this->actingAs($admin)->post(route('admin-cabang.data-akademik.tahun-ajaran.kembalikan-ganjil', $tahunAjaran));

        $tp = \App\Models\TujuanPembelajaran::factory()->create([
            'penugasan_mengajar_id' => $data['penugasan']->id,
        ]);

        $response = $this->actingAs($data['guru'])->post(
            route('guru.kelola-nilai.input-nilai-tp.store', $data['penugasan']),
            ['nilai' => [$data['siswa']->id.'_'.$tp->id => 88]],
        );

        $response->assertRedirect(route('guru.kelola-nilai.input-nilai-tp.show', $data['penugasan']));
        $this->assertDatabaseHas('nilai_tujuan_pembelajaran', [
            'semester_id' => $ganjil->id,
            'siswa_id' => $data['siswa']->id,
            'tujuan_pembelajaran_id' => $tp->id,
            'nilai' => 88,
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
