<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('tempat_lahir')->nullable()->after('tanggal_lahir');
            $table->string('agama', 30)->nullable()->after('tempat_lahir');
            $table->string('status_dalam_keluarga', 50)->nullable()->after('agama');
            $table->unsignedTinyInteger('anak_ke')->nullable()->after('status_dalam_keluarga');
            $table->text('alamat')->nullable()->after('anak_ke');
            $table->string('kecamatan')->nullable()->after('alamat');
            $table->string('kabupaten_kota')->nullable()->after('kecamatan');
            $table->string('nama_ayah')->nullable()->after('kabupaten_kota');
            $table->string('nama_ibu')->nullable()->after('nama_ayah');
            $table->text('alamat_orang_tua')->nullable()->after('nama_ibu');
            $table->string('nomor_telepon_rumah', 30)->nullable()->after('alamat_orang_tua');
            $table->string('pekerjaan_ayah')->nullable()->after('nomor_telepon_rumah');
            $table->string('pekerjaan_ibu')->nullable()->after('pekerjaan_ayah');
            $table->string('nama_wali_siswa')->nullable()->after('pekerjaan_ibu');
            $table->text('alamat_wali_siswa')->nullable()->after('nama_wali_siswa');
            $table->string('nomor_telepon_wali_siswa', 30)->nullable()->after('alamat_wali_siswa');
            $table->string('pekerjaan_wali_siswa')->nullable()->after('nomor_telepon_wali_siswa');
        });

        DB::table('siswa')->whereNull('nisn')->update(['nisn' => '']);

        Schema::table('siswa', function (Blueprint $table) {
            $table->string('nisn', 30)->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('nisn', 30)->nullable()->change();
        });

        Schema::table('siswa', function (Blueprint $table) {
            $table->dropColumn([
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
            ]);
        });
    }
};
