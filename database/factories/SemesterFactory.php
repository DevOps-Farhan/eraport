<?php

namespace Database\Factories;

use App\Models\Semester;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Semester>
 */
class SemesterFactory extends Factory
{
    protected $model = Semester::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tahun_ajaran_id' => TahunAjaran::factory(),
            'jenis' => 'ganjil',
            'tanggal_mulai' => now(),
            'tanggal_selesai' => null,
            'status' => 'berjalan',
        ];
    }
}
