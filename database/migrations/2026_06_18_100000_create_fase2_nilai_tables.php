<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tujuan_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_mengajar_id')->constrained('penugasan_mengajar')->cascadeOnDelete();
            $table->string('kode', 20);
            $table->text('deskripsi_kompetensi');
            $table->unsignedInteger('urutan')->default(1);
            $table->timestamps();

            $table->unique(['penugasan_mengajar_id', 'kode']);
        });

        Schema::create('nilai_tujuan_pembelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tujuan_pembelajaran_id')->constrained('tujuan_pembelajaran')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2);
            $table->string('predikat', 2);
            $table->text('deskripsi_pencapaian');
            $table->timestamps();

            $table->unique(['tujuan_pembelajaran_id', 'siswa_id', 'semester_id'], 'nilai_tp_unique');
        });

        Schema::create('nilai_ujian', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_mengajar_id')->constrained('penugasan_mengajar')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2);
            $table->timestamps();

            $table->unique(['penugasan_mengajar_id', 'siswa_id', 'semester_id'], 'nilai_ujian_unique');
        });

        Schema::create('absensi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->unsignedInteger('sakit')->default(0);
            $table->unsignedInteger('izin')->default(0);
            $table->unsignedInteger('alpha')->default(0);
            $table->timestamps();

            $table->unique(['siswa_id', 'semester_id']);
        });

        Schema::create('catatan_wali_kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['siswa_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('catatan_wali_kelas');
        Schema::dropIfExists('absensi_siswa');
        Schema::dropIfExists('nilai_ujian');
        Schema::dropIfExists('nilai_tujuan_pembelajaran');
        Schema::dropIfExists('tujuan_pembelajaran');
    }
};
