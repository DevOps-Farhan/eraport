<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('anggota_ekstrakurikuler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikuler')->cascadeOnDelete();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['ekstrakurikuler_id', 'tahun_ajaran_id', 'siswa_id'], 'anggota_ekskul_unique');
        });

        Schema::create('nilai_ekstrakurikuler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikuler')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->decimal('nilai', 5, 2)->nullable();
            $table->string('predikat', 2)->nullable();
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->unique(['ekstrakurikuler_id', 'siswa_id', 'semester_id'], 'nilai_ekskul_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilai_ekstrakurikuler');
        Schema::dropIfExists('anggota_ekstrakurikuler');
    }
};
