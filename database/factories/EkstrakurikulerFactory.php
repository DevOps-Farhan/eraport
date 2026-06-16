<?php

namespace Database\Factories;

use App\Models\CabangSekolah;
use App\Models\Ekstrakurikuler;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Ekstrakurikuler>
 */
class EkstrakurikulerFactory extends Factory
{
    protected $model = Ekstrakurikuler::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'cabang_sekolah_id' => CabangSekolah::factory(),
            'kode' => strtoupper(fake()->unique()->lexify('???')),
            'nama' => fake()->words(2, true),
            'aktif' => true,
        ];
    }
}
