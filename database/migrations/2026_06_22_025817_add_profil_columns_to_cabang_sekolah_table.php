<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cabang_sekolah', function (Blueprint $table) {
            $table->string('npsn', 8)->nullable()->after('telepon');
            $table->string('nss')->nullable()->after('npsn');
            $table->string('desa_kelurahan')->nullable()->after('nss');
            $table->string('kecamatan')->nullable()->after('desa_kelurahan');
            $table->string('kabupaten_kota')->nullable()->after('kecamatan');
            $table->string('provinsi')->nullable()->after('kabupaten_kota');
            $table->string('website')->nullable()->after('provinsi');
            $table->string('email')->nullable()->after('website');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cabang_sekolah', function (Blueprint $table) {
            $table->dropColumn([
                'npsn',
                'nss',
                'desa_kelurahan',
                'kecamatan',
                'kabupaten_kota',
                'provinsi',
                'website',
                'email',
            ]);
        });
    }
};
