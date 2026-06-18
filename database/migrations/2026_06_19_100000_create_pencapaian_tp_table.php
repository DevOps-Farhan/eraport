<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pencapaian_tp', function (Blueprint $table) {
            $table->id();
            $table->foreignId('penugasan_mengajar_id')->constrained('penugasan_mengajar')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semester')->cascadeOnDelete();
            $table->text('pencapaian');
            $table->timestamps();

            $table->unique(['penugasan_mengajar_id', 'siswa_id', 'semester_id'], 'pencapaian_tp_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pencapaian_tp');
    }
};
