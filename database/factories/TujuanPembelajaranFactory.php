<?php

namespace Database\Factories;

use App\Models\PenugasanMengajar;
use App\Models\TujuanPembelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TujuanPembelajaran>
 */
class TujuanPembelajaranFactory extends Factory
{
    protected $model = TujuanPembelajaran::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'penugasan_mengajar_id' => PenugasanMengajar::factory(),
            'kode' => fake()->numerify('#.#'),
            'deskripsi_kompetensi' => 'Siswa mampu menjelaskan materi pembelajaran.',
            'urutan' => 1,
        ];
    }
}
