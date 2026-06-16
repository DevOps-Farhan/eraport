<?php

namespace Database\Factories;

use App\Enums\TingkatKelas;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kelas>
 */
class KelasFactory extends Factory
{
    protected $model = Kelas::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tingkat = fake()->randomElement(TingkatKelas::cases());

        return [
            'tahun_ajaran_id' => TahunAjaran::factory()->aktif(),
            'jurusan_id' => Jurusan::factory(),
            'tingkat' => $tingkat,
            'nama' => $tingkat->value.' '.fake()->numerify('#'),
            'wali_kelas_id' => null,
        ];
    }
}
