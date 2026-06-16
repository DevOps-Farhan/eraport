<?php

namespace Database\Factories;

use App\Enums\KelompokMataPelajaran;
use App\Models\CabangSekolah;
use App\Models\MataPelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MataPelajaran>
 */
class MataPelajaranFactory extends Factory
{
    protected $model = MataPelajaran::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cabang_sekolah_id' => CabangSekolah::factory(),
            'kode' => strtoupper(fake()->unique()->lexify('????')),
            'nama' => fake()->words(2, true),
            'kelompok' => fake()->randomElement(KelompokMataPelajaran::cases()),
            'aktif' => true,
        ];
    }
}
