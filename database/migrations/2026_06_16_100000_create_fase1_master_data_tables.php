<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jurusan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_sekolah_id')->constrained('cabang_sekolah')->cascadeOnDelete();
            $table->string('kode', 20);
            $table->string('nama');
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['cabang_sekolah_id', 'kode']);
        });

        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_sekolah_id')->constrained('cabang_sekolah')->cascadeOnDelete();
            $table->string('kode', 20);
            $table->string('nama');
            $table->string('kelompok');
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['cabang_sekolah_id', 'kode']);
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('jurusan_id')->constrained('jurusan')->restrictOnDelete();
            $table->string('tingkat', 3);
            $table->string('nama');
            $table->foreignId('wali_kelas_id')->nullable()->constrained('pengguna')->nullOnDelete();
            $table->timestamps();

            $table->unique(['tahun_ajaran_id', 'nama']);
        });

        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_sekolah_id')->constrained('cabang_sekolah')->cascadeOnDelete();
            $table->string('nis', 30);
            $table->string('nisn', 30)->nullable();
            $table->string('nama');
            $table->string('jenis_kelamin', 1);
            $table->date('tanggal_lahir')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['cabang_sekolah_id', 'nis']);
        });

        Schema::create('rombel_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('status')->default('aktif');
            $table->timestamps();

            $table->unique(['tahun_ajaran_id', 'siswa_id']);
        });

        Schema::create('penugasan_mengajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('guru_id')->constrained('pengguna')->cascadeOnDelete();
            $table->foreignId('mata_pelajaran_id')->constrained('mata_pelajaran')->restrictOnDelete();
            $table->foreignId('kelas_id')->constrained('kelas')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['tahun_ajaran_id', 'guru_id', 'mata_pelajaran_id', 'kelas_id'], 'penugasan_mengajar_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasan_mengajar');
        Schema::dropIfExists('rombel_siswa');
        Schema::dropIfExists('siswa');
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('mata_pelajaran');
        Schema::dropIfExists('jurusan');
    }
};
