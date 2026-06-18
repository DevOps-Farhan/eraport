<?php

namespace Tests\Feature\Guru;

use App\Models\NilaiTujuanPembelajaran;
use App\Models\PencapaianTp;
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
        $this->assertSame('A', $hasilSb['predikat']->value);
        $this->assertStringContainsString('sangat mampu', $hasilSb['deskripsi_pencapaian']);

        $hasilD = $service->generate('Siswa mampu menjelaskan materi.', 65);
        $this->assertSame('D', $hasilD['predikat']->value);
        $this->assertStringContainsString('belum mampu', $hasilD['deskripsi_pencapaian']);
    }

    public function test_generate_pencapaian_ringkas_tp_tertinggi_dan_terendah(): void
    {
        $service = new DeskripsiPencapaianService;
        $data = $this->siapkanDataGuru();

        $tpTinggi = TujuanPembelajaran::factory()->create([
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'kode' => '3.1',
            'deskripsi_kompetensi' => 'Siswa mampu menjelaskan konsep dasar.',
            'urutan' => 1,
        ]);
        $tpRendah = TujuanPembelajaran::factory()->create([
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'kode' => '3.2',
            'deskripsi_kompetensi' => 'Siswa mampu menganalisis masalah.',
            'urutan' => 2,
        ]);

        $nilaiTinggi = new NilaiTujuanPembelajaran([
            'nilai' => 90,
            'tujuan_pembelajaran_id' => $tpTinggi->id,
        ]);
        $nilaiTinggi->setRelation('tujuanPembelajaran', $tpTinggi);

        $nilaiRendah = new NilaiTujuanPembelajaran([
            'nilai' => 65,
            'tujuan_pembelajaran_id' => $tpRendah->id,
        ]);
        $nilaiRendah->setRelation('tujuanPembelajaran', $tpRendah);

        $pencapaian = $service->generatePencapaianRingkas(collect([$nilaiTinggi, $nilaiRendah]));

        $this->assertStringContainsString('menunjukkan capaian yang baik pada menjelaskan konsep dasar', $pencapaian);
        $this->assertStringContainsString('perlu bimbingan pada menganalisis masalah', $pencapaian);
    }

    public function test_guru_dapat_simpan_nilai_tp_dan_pencapaian(): void
    {
        $data = $this->siapkanDataGuru();
        $tpTinggi = TujuanPembelajaran::factory()->create([
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'deskripsi_kompetensi' => 'Siswa mampu menjelaskan materi.',
            'urutan' => 1,
        ]);
        $tpRendah = TujuanPembelajaran::factory()->create([
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'deskripsi_kompetensi' => 'Siswa mampu menganalisis masalah.',
            'urutan' => 2,
        ]);

        $response = $this->actingAs($data['guru'])->post(
            route('guru.kelola-nilai.input-nilai-tp.store', $data['penugasan']),
            [
                'nilai' => [
                    $data['siswa']->id.'_'.$tpTinggi->id => 90,
                    $data['siswa']->id.'_'.$tpRendah->id => 65,
                ],
            ],
        );

        $response->assertRedirect(route('guru.kelola-nilai.input-nilai-tp.show', $data['penugasan']));
        $this->assertDatabaseHas('pencapaian_tp', [
            'penugasan_mengajar_id' => $data['penugasan']->id,
            'siswa_id' => $data['siswa']->id,
            'semester_id' => $data['semester']->id,
        ]);

        $pencapaian = PencapaianTp::query()->first();
        $this->assertStringContainsString('menunjukkan capaian yang baik pada', $pencapaian->pencapaian);
        $this->assertStringContainsString('perlu bimbingan pada', $pencapaian->pencapaian);
    }

    public function test_halaman_input_nilai_tp_menampilkan_kolom_pencapaian(): void
    {
        $data = $this->siapkanDataGuru();
        $tp = TujuanPembelajaran::factory()->create([
            'penugasan_mengajar_id' => $data['penugasan']->id,
        ]);

        $this->actingAs($data['guru'])->post(
            route('guru.kelola-nilai.input-nilai-tp.store', $data['penugasan']),
            ['nilai' => [$data['siswa']->id.'_'.$tp->id => 85]],
        );

        $response = $this->actingAs($data['guru'])->get(
            route('guru.kelola-nilai.input-nilai-tp.show', $data['penugasan']),
        );

        $response->assertOk();
        $response->assertSee('Pencapaian');
        $response->assertSee('menunjukkan capaian yang baik pada');
    }
}
