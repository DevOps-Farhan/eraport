<?php

namespace App\Http\Requests\Concerns;

use App\Enums\Agama;
use App\Enums\JenisKelamin;
use Illuminate\Validation\Rule;

trait RulesProfilSiswa
{
    /**
     * @return array<string, mixed>
     */
    protected function rulesProfilSiswa(int $cabangId, ?int $ignoreSiswaId = null, bool $includeAktif = false): array
    {
        $rules = [
            'nis' => [
                'required',
                'string',
                'max:30',
                Rule::unique('siswa', 'nis')
                    ->where('cabang_sekolah_id', $cabangId)
                    ->ignore($ignoreSiswaId),
            ],
            'nisn' => ['required', 'string', 'max:30'],
            'nama' => ['required', 'string', 'max:150'],
            'jenis_kelamin' => ['nullable', Rule::enum(JenisKelamin::class)],
            'tanggal_lahir' => ['nullable', 'date'],
            'tempat_lahir' => ['nullable', 'string', 'max:100'],
            'agama' => ['nullable', Rule::enum(Agama::class)],
            'status_dalam_keluarga' => ['nullable', 'string', 'max:50'],
            'anak_ke' => ['nullable', 'integer', 'min:1', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:500'],
            'kecamatan' => ['nullable', 'string', 'max:100'],
            'kabupaten_kota' => ['nullable', 'string', 'max:100'],
            'nama_ayah' => ['nullable', 'string', 'max:150'],
            'nama_ibu' => ['nullable', 'string', 'max:150'],
            'alamat_orang_tua' => ['nullable', 'string', 'max:500'],
            'nomor_telepon_rumah' => ['nullable', 'string', 'max:30'],
            'pekerjaan_ayah' => ['nullable', 'string', 'max:100'],
            'pekerjaan_ibu' => ['nullable', 'string', 'max:100'],
            'nama_wali_siswa' => ['nullable', 'string', 'max:150'],
            'alamat_wali_siswa' => ['nullable', 'string', 'max:500'],
            'nomor_telepon_wali_siswa' => ['nullable', 'string', 'max:30'],
            'pekerjaan_wali_siswa' => ['nullable', 'string', 'max:100'],
        ];

        if ($includeAktif) {
            $rules['aktif'] = ['sometimes', 'boolean'];
        }

        return $rules;
    }
}
