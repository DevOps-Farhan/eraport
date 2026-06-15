<?php

namespace Database\Seeders;

use App\Enums\PeranPengguna;
use App\Models\CabangSekolah;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PenggunaSeeder extends Seeder
{
    public function run(): void
    {
        $cabang = CabangSekolah::query()->first();

        User::query()->create([
            'username' => 'superadmin',
            'nama' => 'Admin Pusat',
            'email' => 'admin@eraport.test',
            'password' => Hash::make('password'),
            'peran' => PeranPengguna::AdminPusat,
            'cabang_sekolah_id' => null,
            'aktif' => true,
        ]);

        User::query()->create([
            'username' => 'admin.jakarta',
            'nama' => 'Admin Cabang Jakarta',
            'email' => 'admin.jakarta@eraport.test',
            'password' => Hash::make('password'),
            'peran' => PeranPengguna::AdminCabang,
            'cabang_sekolah_id' => $cabang?->id,
            'aktif' => true,
        ]);

        User::query()->create([
            'username' => 'guru.001',
            'nama' => 'Budi Santoso',
            'email' => 'guru.001@eraport.test',
            'password' => Hash::make('password'),
            'peran' => PeranPengguna::Guru,
            'cabang_sekolah_id' => $cabang?->id,
            'aktif' => true,
        ]);

        User::query()->create([
            'username' => 'pembina.pramuka',
            'nama' => 'Siti Aminah',
            'email' => 'pembina.pramuka@eraport.test',
            'password' => Hash::make('password'),
            'peran' => PeranPengguna::PembinaEkstrakurikuler,
            'cabang_sekolah_id' => $cabang?->id,
            'aktif' => true,
        ]);
    }
}
