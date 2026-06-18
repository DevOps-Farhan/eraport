<?php

namespace Tests\Feature\AdminCabang;

use App\Models\CabangSekolah;
use App\Models\Siswa;
use App\Models\User;
use App\Services\ImportSiswaCsvService;
use App\Support\SiswaCsv;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class ImportSiswaCsvTest extends TestCase
{
    use RefreshDatabase;

    private function adminCabang(CabangSekolah $cabang): User
    {
        return User::factory()->adminCabang()->create(['cabang_sekolah_id' => $cabang->id]);
    }

    public function test_admin_dapat_unduh_template_csv(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabang);

        $response = $this->actingAs($admin)->get(route('admin-cabang.master-data.siswa.template-csv'));

        $response->assertOk();
        $response->assertHeader('content-disposition');
        $this->assertStringContainsString('nis,nisn,nama', $response->streamedContent());
    }

    public function test_admin_dapat_import_siswa_dari_csv(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabang);

        $csv = $this->buatCsv([
            ['nis' => '2026101', 'nisn' => '1111111111', 'nama' => 'Andi Pratama', 'jenis_kelamin' => 'L', 'aktif' => '1'],
            ['nis' => '2026102', 'nisn' => '2222222222', 'nama' => 'Dewi Lestari', 'jenis_kelamin' => 'P', 'aktif' => '1'],
        ]);

        $file = UploadedFile::fake()->createWithContent('siswa.csv', $csv);

        $response = $this->actingAs($admin)->post(route('admin-cabang.master-data.siswa.import-csv'), [
            'file' => $file,
        ]);

        $response->assertRedirect(route('admin-cabang.master-data.siswa.index'));
        $response->assertSessionHas('sukses');

        $this->assertDatabaseHas('siswa', ['cabang_sekolah_id' => $cabang->id, 'nis' => '2026101', 'nama' => 'Andi Pratama']);
        $this->assertDatabaseHas('siswa', ['cabang_sekolah_id' => $cabang->id, 'nis' => '2026102', 'nama' => 'Dewi Lestari']);
        $this->assertDatabaseCount('siswa', 2);
    }

    public function test_import_gagal_jika_nis_duplikat_di_database(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabang);
        Siswa::factory()->create(['cabang_sekolah_id' => $cabang->id, 'nis' => '2026101']);

        $csv = $this->buatCsv([
            ['nis' => '2026101', 'nisn' => '3333333333', 'nama' => 'Duplikat', 'aktif' => '1'],
        ]);

        $file = UploadedFile::fake()->createWithContent('siswa.csv', $csv);

        $response = $this->actingAs($admin)->post(route('admin-cabang.master-data.siswa.import-csv'), [
            'file' => $file,
        ]);

        $response->assertSessionHas('import_kesalahan');
        $this->assertDatabaseCount('siswa', 1);
    }

    public function test_import_melewati_baris_contoh_template(): void
    {
        $cabang = CabangSekolah::factory()->create();
        $admin = $this->adminCabang($cabang);

        $konten = app(ImportSiswaCsvService::class)->kontenTemplate();
        $file = UploadedFile::fake()->createWithContent('siswa.csv', $konten);

        $response = $this->actingAs($admin)->post(route('admin-cabang.master-data.siswa.import-csv'), [
            'file' => $file,
        ]);

        $response->assertSessionHas('sukses');
        $this->assertDatabaseCount('siswa', 1);
        $this->assertDatabaseHas('siswa', ['nis' => '2026002', 'nama' => 'Citra Dewi']);
    }

    /**
     * @param  list<array<string, string>>  $baris
     */
    private function buatCsv(array $baris): string
    {
        $kolom = SiswaCsv::kolom();
        $lines = [implode(',', $kolom)];

        foreach ($baris as $item) {
            $nilai = array_map(fn (string $k) => $item[$k] ?? '', $kolom);
            $handle = fopen('php://temp', 'r+');
            fputcsv($handle, $nilai);
            rewind($handle);
            $lines[] = rtrim(stream_get_contents($handle) ?: '', "\n");
            fclose($handle);
        }

        return implode("\n", $lines);
    }
}
