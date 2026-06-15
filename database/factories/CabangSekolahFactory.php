<?php

namespace Database\Factories;

use App\Models\CabangSekolah;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CabangSekolah>
 */
class CabangSekolahFactory extends Factory
{
    protected $model = CabangSekolah::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => fake()->unique()->bothify('CS-###'),
            'nama' => fake()->company().' School',
            'kepala_sekolah' => fake()->name(),
            'alamat' => fake()->address(),
            'telepon' => fake()->phoneNumber(),
            'aktif' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'aktif' => false,
        ]);
    }
}
