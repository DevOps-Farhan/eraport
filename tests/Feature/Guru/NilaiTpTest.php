<?php

namespace Tests\Feature\Guru;

use App\Models\NilaiTujuanPembelajaran;
use App\Models\TujuanPembelajaran;
use App\Services\DeskripsiPencapaianService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NilaiTpTest extends TestCase
{
    use MenyiapkanDataGuru;
    use RefreshDatabase;

    public function test_deskripsi_pencapaian_auto_generate(): void
    {
        $service = new DeskripsiPencapaianService;

        $hasilSb = $service->generate('Siswa mampu menjelaskan materi.', 92);
        $this->assertSame('SB', $hasilSb['predikat']->value);
        $this->assertStringContainsString('sangat mampu', $hasilSb['deskripsi_pencapaian']);

        $hasilD = $service->generate('Siswa mampu menjelaskan materi.', 65);
        $this->assertSame('D', $hasilD['predikat']->value);
        $this->assertStringContainsString('belum mampu', $hasilD['deskripsi_pencapaian']);
    }

    public function test_guru_dapat_simpan_nilai_tp(): void
    {
        $data = $this->siapkanDataGuru();
        $tp = TujuanPembelajaran::factory()->create([
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'deskripsi_kompetensi' => 'Siswa mampu menjelaskan materi.',
        ]);

        $key = $data['siswa']->id.'_'.$tp->id;

        $response = $this->actingAs($data['guru'])->post(
            route('guru.kelola-nilai.input-nilai-tp.store', $data['penugasan']),
            ['nilai' => [$key => 85]],
        );

        $response->assertRedirect(route('guru.kelola-nilai.input-nilai-tp.show', $data['penugasan']));
        $this->assertDatabaseHas('nilai_tujuan_pembelajaran', [
            'tujuan_pembelajaran_id' => $tp->id,
            'siswa_id' => $data['siswa']->id,
            'semester_id' => $data['semester']->id,
            'predikat' => 'B',
        ]);

        $nilai = NilaiTujuanPembelajaran::query()->first();
        $this->assertStringContainsString('mampu', $nilai->deskripsi_pencapaian);
    }
}
