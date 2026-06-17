<?php

namespace Database\Factories;

use App\Models\AbsensiSiswa;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AbsensiSiswa>
 */
class AbsensiSiswaFactory extends Factory
{
    protected $model = AbsensiSiswa::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'siswa_id' => Siswa::factory(),
            'semester_id' => Semester::factory(),
            'sakit' => 0,
            'izin' => 0,
            'alpha' => 0,
        ];
    }
}
