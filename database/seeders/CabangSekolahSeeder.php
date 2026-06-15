<?php

namespace Database\Seeders;

use App\Models\CabangSekolah;
use Illuminate\Database\Seeder;

class CabangSekolahSeeder extends Seeder
{
    public function run(): void
    {
        CabangSekolah::query()->create([
            'kode' => 'CS-JKT-01',
            'nama' => 'SMK Negeri 1 Jakarta',
            'alamat' => 'Jl. Pendidikan No. 1, Jakarta Pusat',
            'telepon' => '021-12345678',
            'aktif' => true,
        ]);
    }
}
