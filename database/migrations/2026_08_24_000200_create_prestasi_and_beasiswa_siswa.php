<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('prestasi_siswa', function (Blueprint $t) {
            $t->id();
            $t->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $t->string('jenis', 100)->nullable();
            $t->string('tingkat', 50)->nullable();
            $t->string('nama', 191)->nullable();
            $t->year('tahun')->nullable();
            $t->string('penyelenggara', 191)->nullable();
            $t->timestamps();
        });

        Schema::create('beasiswa_siswa', function (Blueprint $t) {
            $t->id();
            $t->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $t->string('jenis', 100)->nullable();
            $t->text('keterangan')->nullable();
            $t->year('tahun_mulai')->nullable();
            $t->year('tahun_selesai')->nullable();
            $t->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beasiswa_siswa');
        Schema::dropIfExists('prestasi_siswa');
    }
};
