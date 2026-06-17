<?php

namespace Tests\Feature\Guru;

use App\Models\NilaiTujuanPembelajaran;
use App\Models\TujuanPembelajaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TujuanPembelajaranTest extends TestCase
{
    use MenyiapkanDataGuru;
    use RefreshDatabase;

    public function test_guru_dapat_menambah_tujuan_pembelajaran(): void
    {
        $data = $this->siapkanDataGuru();

        $response = $this->actingAs($data['guru'])->post(route('guru.kelola-nilai.tujuan-pembelajaran.store'), [
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'kode' => '3.1',
            'deskripsi_kompetensi' => 'Siswa mampu menjelaskan konsep dasar.',
            'urutan' => 1,
        ]);

        $response->assertRedirect(route('guru.kelola-nilai.tujuan-pembelajaran', ['penugasan' => $data['penugasan']->id]));
        $this->assertDatabaseHas('tujuan_pembelajaran', [
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'kode' => '3.1',
        ]);
    }

    public function test_guru_tidak_dapat_akses_penugasan_orang_lain(): void
    {
        $data = $this->siapkanDataGuru();
        $guruLain = User::factory()->guru()->create(['cabang_sekolah_id' => $data['cabang']->id]);

        $response = $this->actingAs($guruLain)->post(route('guru.kelola-nilai.tujuan-pembelajaran.store'), [
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'kode' => '3.1',
            'deskripsi_kompetensi' => 'Siswa mampu menjelaskan konsep dasar.',
            'urutan' => 1,
        ]);

        $response->assertSessionHasErrors('penugasan_mengajar_id');
    }

    public function test_tidak_bisa_hapus_tp_yang_sudah_ada_nilai(): void
    {
        $data = $this->siapkanDataGuru();
        $tp = TujuanPembelajaran::factory()->create([
            'penugasan_mengajar_id' => $data['penugasan']->id,
        ]);

        NilaiTujuanPembelajaran::factory()->create([
            'tujuan_pembelajaran_id' => $tp->id,
            'siswa_id' => $data['siswa']->id,
            'semester_id' => $data['semester']->id,
        ]);

        $response = $this->actingAs($data['guru'])->delete(route('guru.kelola-nilai.tujuan-pembelajaran.destroy', $tp));

        $response->assertRedirect();
        $response->assertSessionHas('gagal');
        $this->assertDatabaseHas('tujuan_pembelajaran', ['id' => $tp->id]);
    }
}
