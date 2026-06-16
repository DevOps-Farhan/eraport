<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ekstrakurikuler', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cabang_sekolah_id')->constrained('cabang_sekolah')->cascadeOnDelete();
            $table->string('kode', 20);
            $table->string('nama');
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['cabang_sekolah_id', 'kode']);
        });

        Schema::create('penugasan_pembina', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->cascadeOnDelete();
            $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikuler')->restrictOnDelete();
            $table->foreignId('pembina_id')->constrained('pengguna')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['tahun_ajaran_id', 'ekstrakurikuler_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penugasan_pembina');
        Schema::dropIfExists('ekstrakurikuler');
    }
};
