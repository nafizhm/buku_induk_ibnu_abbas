<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jenjang_kelas', function (Blueprint $table) {
            $table->id();
            $table->string('jenjang');
            $table->timestamps();
        });

        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('tahun');
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        Schema::create('kelas', function (Blueprint $table) {
            $table->increments('id_kelas');
            $table->string('nama_kelas', 50);
            $table->string('tingkat', 80)->default('');
            $table->string('wali_kelas', 100)->default('');
            $table->string('jadwal', 120)->default('');
            $table->enum('status', ['aktif', 'non aktif'])->default('aktif');
            $table->enum('jenis', ['banin', 'banat'])->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
        Schema::dropIfExists('tahun_ajaran');
        Schema::dropIfExists('jenjang_kelas');
    }
};
