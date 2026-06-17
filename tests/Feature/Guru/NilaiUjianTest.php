<?php

namespace Tests\Feature\Guru;

use App\Models\NilaiTujuanPembelajaran;
use App\Models\NilaiUjian;
use App\Models\TujuanPembelajaran;
use App\Services\NilaiRaportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NilaiUjianTest extends TestCase
{
    use MenyiapkanDataGuru;
    use RefreshDatabase;

    public function test_formula_nilai_akhir_60_40(): void
    {
        $service = new NilaiRaportService;

        $nilaiAkhir = $service->hitungNilaiAkhir(80.0, 70.0);

        $this->assertSame(76.0, $nilaiAkhir);
    }

    public function test_guru_dapat_simpan_nilai_ujian(): void
    {
        $data = $this->siapkanDataGuru();
        $tp = TujuanPembelajaran::factory()->create(['penugasan_mengajar_id' => $data['penugasan']->id]);
        NilaiTujuanPembelajaran::factory()->create([
            'tujuan_pembelajaran_id' => $tp->id,
            'siswa_id' => $data['siswa']->id,
            'semester_id' => $data['semester']->id,
            'nilai' => 80,
        ]);

        $response = $this->actingAs($data['guru'])->post(
            route('guru.kelola-nilai.nilai-ujian.store', $data['penugasan']),
            ['nilai' => [$data['siswa']->id => 90]],
        );

        $response->assertRedirect(route('guru.kelola-nilai.nilai-ujian.show', $data['penugasan']));
        $this->assertDatabaseHas('nilai_ujian', [
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'siswa_id' => $data['siswa']->id,
            'nilai' => 90,
        ]);
    }

    public function test_ringkasan_mapel_menghitung_nilai_akhir(): void
    {
        $data = $this->siapkanDataGuru();
        $tp = TujuanPembelajaran::factory()->create(['penugasan_mengajar_id' => $data['penugasan']->id]);
        NilaiTujuanPembelajaran::factory()->create([
            'tujuan_pembelajaran_id' => $tp->id,
            'siswa_id' => $data['siswa']->id,
            'semester_id' => $data['semester']->id,
            'nilai' => 80,
        ]);
        NilaiUjian::factory()->create([
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'siswa_id' => $data['siswa']->id,
            'semester_id' => $data['semester']->id,
            'nilai' => 70,
        ]);

        $ringkasan = (new NilaiRaportService)->ringkasanMapel($data['penugasan'], $data['siswa'], $data['semester']);

        $this->assertSame(76.0, $ringkasan['nilai_akhir']);
    }
}
