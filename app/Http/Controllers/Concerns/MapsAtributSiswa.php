<?php

namespace App\Http\Controllers\Concerns;

use App\Enums\Agama;
use App\Enums\JenisKelamin;
use Illuminate\Http\Request;

trait MapsAtributSiswa
{
    /**
     * @return array<string, mixed>
     */
    protected function atributSiswaDariRequest(Request $request, bool $includeAktif = false): array
    {
        $data = [
            'nis' => $request->string('nis')->value(),
            'nisn' => $request->string('nisn')->value(),
            'nama' => $request->string('nama')->value(),
            'jenis_kelamin' => $request->filled('jenis_kelamin')
                ? $request->enum('jenis_kelamin', JenisKelamin::class)
                : null,
            'tanggal_lahir' => $request->date('tanggal_lahir'),
            'tempat_lahir' => $request->string('tempat_lahir')->value() ?: null,
            'agama' => $request->filled('agama')
                ? $request->enum('agama', Agama::class)
                : null,
            'status_dalam_keluarga' => $request->string('status_dalam_keluarga')->value() ?: null,
            'anak_ke' => $request->filled('anak_ke') ? $request->integer('anak_ke') : null,
            'alamat' => $request->string('alamat')->value() ?: null,
            'kecamatan' => $request->string('kecamatan')->value() ?: null,
            'kabupaten_kota' => $request->string('kabupaten_kota')->value() ?: null,
            'nama_ayah' => $request->string('nama_ayah')->value() ?: null,
            'nama_ibu' => $request->string('nama_ibu')->value() ?: null,
            'alamat_orang_tua' => $request->string('alamat_orang_tua')->value() ?: null,
            'nomor_telepon_rumah' => $request->string('nomor_telepon_rumah')->value() ?: null,
            'pekerjaan_ayah' => $request->string('pekerjaan_ayah')->value() ?: null,
            'pekerjaan_ibu' => $request->string('pekerjaan_ibu')->value() ?: null,
            'nama_wali_siswa' => $request->string('nama_wali_siswa')->value() ?: null,
            'alamat_wali_siswa' => $request->string('alamat_wali_siswa')->value() ?: null,
            'nomor_telepon_wali_siswa' => $request->string('nomor_telepon_wali_siswa')->value() ?: null,
            'pekerjaan_wali_siswa' => $request->string('pekerjaan_wali_siswa')->value() ?: null,
        ];

        if ($includeAktif) {
            $data['aktif'] = $request->boolean('aktif', true);
        }

        return $data;
    }
}
