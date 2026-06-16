<?php

namespace Database\Factories;

use App\Enums\PeranPengguna;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PenugasanMengajar;
use App\Models\TahunAjaran;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenugasanMengajar>
 */
class PenugasanMengajarFactory extends Factory
{
    protected $model = PenugasanMengajar::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tahun_ajaran_id' => TahunAjaran::factory()->aktif(),
            'guru_id' => User::factory()->guru(),
            'mata_pelajaran_id' => MataPelajaran::factory(),
            'kelas_id' => Kelas::factory(),
        ];
    }

    public function configure(): static
    {
        return $this->afterMaking(function (PenugasanMengajar $penugasan) {
            if ($penugasan->guru && $penugasan->guru->peran !== PeranPengguna::Guru) {
                $penugasan->guru_id = User::factory()->guru()->create([
                    'cabang_sekolah_id' => $penugasan->guru->cabang_sekolah_id,
                ])->id;
            }
        });
    }
}
