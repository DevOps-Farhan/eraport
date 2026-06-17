<?php

namespace Database\Factories;

use App\Models\NilaiUjian;
use App\Models\PenugasanMengajar;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NilaiUjian>
 */
class NilaiUjianFactory extends Factory
{
    protected $model = NilaiUjian::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'penugasan_mengajar_id' => PenugasanMengajar::factory(),
            'siswa_id' => Siswa::factory(),
            'semester_id' => Semester::factory(),
            'nilai' => 80,
        ];
    }
}
