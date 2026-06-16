<?php

namespace Database\Factories;

use App\Enums\JenisSemester;
use App\Enums\StatusSemester;
use App\Enums\StatusTahunAjaran;
use App\Models\CabangSekolah;
use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TahunAjaran>
 */
class TahunAjaranFactory extends Factory
{
    protected $model = TahunAjaran::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $mulai = fake()->dateTimeBetween('-1 year', 'now');

        return [
            'cabang_sekolah_id' => CabangSekolah::factory(),
            'nama' => '2025/2026',
            'tanggal_mulai' => $mulai,
            'tanggal_selesai' => null,
            'status' => StatusTahunAjaran::Draft,
        ];
    }

    public function aktif(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusTahunAjaran::Aktif,
        ])->afterCreating(function (TahunAjaran $tahunAjaran) {
            Semester::query()->create([
                'tahun_ajaran_id' => $tahunAjaran->id,
                'jenis' => JenisSemester::Ganjil,
                'status' => StatusSemester::Berjalan,
                'tanggal_mulai' => $tahunAjaran->tanggal_mulai,
            ]);

            Semester::query()->create([
                'tahun_ajaran_id' => $tahunAjaran->id,
                'jenis' => JenisSemester::Genap,
                'status' => StatusSemester::BelumDimulai,
            ]);
        });
    }

    public function ditutup(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => StatusTahunAjaran::Ditutup,
            'tanggal_selesai' => now(),
        ]);
    }
}
