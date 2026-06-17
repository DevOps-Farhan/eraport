<?php

namespace Tests\Feature\Guru;

use App\Enums\StatusRombel;
use App\Models\CabangSekolah;
use App\Models\Jurusan;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\PenugasanMengajar;
use App\Models\RombelSiswa;
use App\Models\Semester;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;

trait MenyiapkanDataGuru
{
    /**
     * @return array{cabang: CabangSekolah, tahunAjaran: TahunAjaran, semester: Semester, guru: User, kelas: Kelas, mapel: MataPelajaran, penugasan: PenugasanMengajar, siswa: Siswa}
     */
    protected function siapkanDataGuru(): array
    {
        $cabang = CabangSekolah::factory()->create(['kepala_sekolah' => 'Budi Santoso']);
        $tahunAjaran = TahunAjaran::factory()->aktif()->create(['cabang_sekolah_id' => $cabang->id]);
        $semester = $tahunAjaran->semesterBerjalan;
        $guru = User::factory()->guru()->create(['cabang_sekolah_id' => $cabang->id]);
        $jurusan = Jurusan::factory()->create(['cabang_sekolah_id' => $cabang->id]);
        $kelas = Kelas::factory()->create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'jurusan_id' => $jurusan->id,
            'wali_kelas_id' => $guru->id,
        ]);
        $mapel = MataPelajaran::factory()->create(['cabang_sekolah_id' => $cabang->id]);
        $penugasan = PenugasanMengajar::factory()->create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'guru_id' => $guru->id,
            'mata_pelajaran_id' => $mapel->id,
            'kelas_id' => $kelas->id,
        ]);
        $siswa = Siswa::factory()->create(['cabang_sekolah_id' => $cabang->id]);
        RombelSiswa::factory()->create([
            'tahun_ajaran_id' => $tahunAjaran->id,
            'kelas_id' => $kelas->id,
            'siswa_id' => $siswa->id,
            'status' => StatusRombel::Aktif,
        ]);

        return compact('cabang', 'tahunAjaran', 'semester', 'guru', 'kelas', 'mapel', 'penugasan', 'siswa');
    }
}
