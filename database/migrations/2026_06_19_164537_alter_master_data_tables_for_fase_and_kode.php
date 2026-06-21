<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kelas', function (Blueprint $table) {
            $table->enum('fase', ['E', 'F'])->default('E')->after('tingkat');
        });

        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->string('kode', 20)->nullable()->change();
        });

        Schema::table('ekstrakurikuler', function (Blueprint $table) {
            $table->string('kode', 20)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('ekstrakurikuler', function (Blueprint $table) {
            $table->string('kode', 20)->nullable(false)->change();
        });

        Schema::table('mata_pelajaran', function (Blueprint $table) {
            $table->string('kode', 20)->nullable(false)->change();
        });

        Schema::table('kelas', function (Blueprint $table) {
            $table->dropColumn('fase');
        });
    }
};
