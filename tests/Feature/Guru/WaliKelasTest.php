<?php

namespace Tests\Feature\Guru;

use App\Enums\JenisKelamin;
use App\Models\Siswa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WaliKelasTest extends TestCase
{
    use MenyiapkanDataGuru;
    use RefreshDatabase;

    public function test_guru_bukan_wali_kelas_melihat_pesan_informatif(): void
    {
        $data = $this->siapkanDataGuru();
        $guruBiasa = User::factory()->guru()->create(['cabang_sekolah_id' => $data['cabang']->id]);

        $response = $this->actingAs($guruBiasa)->get(route('guru.wali-kelas.kelas'));

        $response->assertOk();
        $response->assertSee('belum ditunjuk sebagai wali kelas');
    }

    public function test_wali_kelas_dapat_tambah_siswa(): void
    {
        $data = $this->siapkanDataGuru();

        $response = $this->actingAs($data['guru'])->post(route('guru.wali-kelas.kelas.store-siswa'), [
            'nis' => '12345',
            'nisn' => '1122334455',
            'nama' => 'Siswa Baru',
            'jenis_kelamin' => JenisKelamin::LakiLaki->value,
        ]);

        $response->assertRedirect(route('guru.wali-kelas.kelas'));
        $this->assertDatabaseHas('siswa', ['nis' => '12345', 'nama' => 'Siswa Baru']);
        $this->assertDatabaseHas('rombel_siswa', [
            'kelas_id' => $data['kelas']->id,
            'siswa_id' => Siswa::query()->where('nis', '12345')->value('id'),
        ]);
    }

    public function test_wali_kelas_dapat_simpan_absensi_dan_catatan(): void
    {
        $data = $this->siapkanDataGuru();

        $response = $this->actingAs($data['guru'])->post(route('guru.wali-kelas.absensi-catatan.store'), [
            'absensi' => [
                $data['siswa']->id => ['sakit' => 2, 'izin' => 1, 'alpha' => 0],
            ],
            'catatan' => [
                $data['siswa']->id => 'Siswa rajin belajar.',
            ],
        ]);

        $response->assertRedirect(route('guru.wali-kelas.absensi-catatan'));
        $this->assertDatabaseHas('absensi_siswa', [
            'siswa_id' => $data['siswa']->id,
            'sakit' => 2,
        ]);
        $this->assertDatabaseHas('catatan_wali_kelas', [
            'siswa_id' => $data['siswa']->id,
            'catatan' => 'Siswa rajin belajar.',
        ]);
    }

    public function test_wali_kelas_dapat_cetak_raport(): void
    {
        $data = $this->siapkanDataGuru();

        $response = $this->actingAs($data['guru'])->get(route('guru.wali-kelas.leger-raport.cetak', $data['siswa']));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/pdf');
    }
}
