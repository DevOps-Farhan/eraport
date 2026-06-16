<?php

namespace Database\Factories;

use App\Enums\PeranPengguna;
use App\Models\Ekstrakurikuler;
use App\Models\PenugasanPembina;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenugasanPembina>
 */
class PenugasanPembinaFactory extends Factory
{
    protected $model = PenugasanPembina::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tahun_ajaran_id' => TahunAjaran::factory()->aktif(),
            'ekstrakurikuler_id' => Ekstrakurikuler::factory(),
            'pembina_id' => User::factory()->pembinaEkstrakurikuler(),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (PenugasanPembina $penugasan) {
            if ($penugasan->pembina && $penugasan->pembina->peran !== PeranPengguna::PembinaEkstrakurikuler) {
                $penugasan->pembina_id = User::factory()->pembinaEkstrakurikuler()->create([
                    'cabang_sekolah_id' => $penugasan->pembina->cabang_sekolah_id,
                ])->id;
            }
        });
    }
}
