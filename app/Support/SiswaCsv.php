<?php

namespace App\Support;

final class SiswaCsv
{
    /**
     * @return list<string>
     */
    public static function kolom(): array
    {
        return [
            'nis',
            'nisn',
            'nama',
            'jenis_kelamin',
            'tanggal_lahir',
            'tempat_lahir',
            'agama',
            'status_dalam_keluarga',
            'anak_ke',
            'alamat',
            'kecamatan',
            'kabupaten_kota',
            'nama_ayah',
            'nama_ibu',
            'alamat_orang_tua',
            'nomor_telepon_rumah',
            'pekerjaan_ayah',
            'pekerjaan_ibu',
            'nama_wali_siswa',
            'alamat_wali_siswa',
            'nomor_telepon_wali_siswa',
            'pekerjaan_wali_siswa',
            'aktif',
        ];
    }

    /**
     * @return list<string>
     */
    public static function kolomWajib(): array
    {
        return ['nis', 'nisn', 'nama'];
    }

    /**
     * @return array<string, string>
     */
    public static function labelKolom(): array
    {
        return [
            'nis' => 'NIS',
            'nisn' => 'NISN',
            'nama' => 'Nama Lengkap',
            'jenis_kelamin' => 'Jenis Kelamin (L/P)',
            'tanggal_lahir' => 'Tanggal Lahir (YYYY-MM-DD)',
            'tempat_lahir' => 'Tempat Lahir',
            'agama' => 'Agama (islam/kristen/katolik/hindu/buddha/konghucu/lainnya)',
            'status_dalam_keluarga' => 'Status dalam Keluarga',
            'anak_ke' => 'Anak Ke',
            'alamat' => 'Alamat',
            'kecamatan' => 'Kecamatan',
            'kabupaten_kota' => 'Kabupaten/Kota',
            'nama_ayah' => 'Nama Ayah',
            'nama_ibu' => 'Nama Ibu',
            'alamat_orang_tua' => 'Alamat Orang Tua',
            'nomor_telepon_rumah' => 'Nomor Telepon Rumah',
            'pekerjaan_ayah' => 'Pekerjaan Ayah',
            'pekerjaan_ibu' => 'Pekerjaan Ibu',
            'nama_wali_siswa' => 'Nama Wali Siswa',
            'alamat_wali_siswa' => 'Alamat Wali Siswa',
            'nomor_telepon_wali_siswa' => 'Nomor Telepon Wali Siswa',
            'pekerjaan_wali_siswa' => 'Pekerjaan Wali Siswa',
            'aktif' => 'Aktif (1/0)',
        ];
    }

    /**
     * @return list<array<string, string>>
     */
    public static function barisContoh(): array
    {
        return [
            [
                'nis' => '2026001',
                'nisn' => '9988776655',
                'nama' => 'Budi Santoso',
                'jenis_kelamin' => 'L',
                'tanggal_lahir' => '2010-05-15',
                'tempat_lahir' => 'Jakarta',
                'agama' => 'islam',
                'status_dalam_keluarga' => 'Kandung',
                'anak_ke' => '1',
                'alamat' => 'Jl. Merdeka No. 10',
                'kecamatan' => 'Cipayung',
                'kabupaten_kota' => 'Jakarta Timur',
                'nama_ayah' => 'Ahmad Santoso',
                'nama_ibu' => 'Siti Rahayu',
                'alamat_orang_tua' => 'Jl. Merdeka No. 10',
                'nomor_telepon_rumah' => '0211234567',
                'pekerjaan_ayah' => 'Wiraswasta',
                'pekerjaan_ibu' => 'Ibu Rumah Tangga',
                'nama_wali_siswa' => '',
                'alamat_wali_siswa' => '',
                'nomor_telepon_wali_siswa' => '',
                'pekerjaan_wali_siswa' => '',
                'aktif' => '1',
            ],
            [
                'nis' => '2026002',
                'nisn' => '1122334455',
                'nama' => 'Citra Dewi',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '2010-08-20',
                'tempat_lahir' => 'Bandung',
                'agama' => 'kristen',
                'status_dalam_keluarga' => '',
                'anak_ke' => '',
                'alamat' => '',
                'kecamatan' => '',
                'kabupaten_kota' => '',
                'nama_ayah' => '',
                'nama_ibu' => '',
                'alamat_orang_tua' => '',
                'nomor_telepon_rumah' => '',
                'pekerjaan_ayah' => '',
                'pekerjaan_ibu' => '',
                'nama_wali_siswa' => '',
                'alamat_wali_siswa' => '',
                'nomor_telepon_wali_siswa' => '',
                'pekerjaan_wali_siswa' => '',
                'aktif' => '1',
            ],
        ];
    }

    public static function namaFileTemplate(): string
    {
        return 'template-import-siswa.csv';
    }
}
