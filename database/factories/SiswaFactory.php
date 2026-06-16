<?php

namespace Database\Factories;

use App\Enums\JenisKelamin;
use App\Models\CabangSekolah;
use App\Models\Siswa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Siswa>
 */
class SiswaFactory extends Factory
{
    protected $model = Siswa::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cabang_sekolah_id' => CabangSekolah::factory(),
            'nis' => fake()->unique()->numerify('####'),
            'nisn' => fake()->optional()->numerify('##########'),
            'nama' => fake()->name(),
            'jenis_kelamin' => fake()->randomElement(JenisKelamin::cases()),
            'tanggal_lahir' => fake()->optional()->date(),
            'aktif' => true,
        ];
    }
}
