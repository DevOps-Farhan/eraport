<?php

namespace Database\Factories;

use App\Enums\PredikatCapaian;
use App\Models\NilaiTujuanPembelajaran;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TujuanPembelajaran;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NilaiTujuanPembelajaran>
 */
class NilaiTujuanPembelajaranFactory extends Factory
{
    protected $model = NilaiTujuanPembelajaran::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tujuan_pembelajaran_id' => TujuanPembelajaran::factory(),
            'siswa_id' => Siswa::factory(),
            'semester_id' => Semester::factory(),
            'nilai' => 85,
            'predikat' => PredikatCapaian::B,
            'deskripsi_pencapaian' => 'Siswa mampu menjelaskan materi pembelajaran.',
        ];
    }
}
