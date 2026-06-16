<?php

namespace Database\Factories;

use App\Enums\StatusRombel;
use App\Models\Kelas;
use App\Models\RombelSiswa;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RombelSiswa>
 */
class RombelSiswaFactory extends Factory
{
    protected $model = RombelSiswa::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tahun_ajaran_id' => TahunAjaran::factory()->aktif(),
            'kelas_id' => Kelas::factory(),
            'siswa_id' => Siswa::factory(),
            'status' => StatusRombel::Aktif,
        ];
    }
}
