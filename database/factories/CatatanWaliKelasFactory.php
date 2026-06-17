<?php

namespace Database\Factories;

use App\Models\CatatanWaliKelas;
use App\Models\Semester;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CatatanWaliKelas>
 */
class CatatanWaliKelasFactory extends Factory
{
    protected $model = CatatanWaliKelas::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'siswa_id' => Siswa::factory(),
            'semester_id' => Semester::factory(),
            'catatan' => fake()->sentence(),
        ];
    }
}
