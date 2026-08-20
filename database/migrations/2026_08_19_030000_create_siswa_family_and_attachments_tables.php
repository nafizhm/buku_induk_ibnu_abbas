<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('data_orang_tua_siswa', function (Blueprint $t) {
            $t->id(); $t->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $t->enum('jenis', ['Ayah','Ibu']); $t->string('nama_lengkap', 200)->nullable(); $t->string('nik', 16)->nullable();
            $t->string('no_kk', 16)->nullable(); $t->string('tempat_lahir', 100)->nullable(); $t->date('tanggal_lahir')->nullable();
            $t->string('agama', 50)->nullable(); $t->string('kewarganegaraan', 50)->nullable(); $t->enum('status_hidup', ['Hidup','Meninggal'])->default('Hidup');
            $t->string('hubungan_dengan_siswa', 100)->nullable(); $t->string('no_hp', 20)->nullable(); $t->string('no_whatsapp', 20)->nullable(); $t->string('email')->nullable();
            $t->boolean('alamat_sama_dengan_siswa')->default(false); $t->text('alamat')->nullable(); $t->string('rt', 5)->nullable(); $t->string('rw', 5)->nullable();
            $t->string('desa_kelurahan', 100)->nullable(); $t->string('kecamatan', 100)->nullable(); $t->string('kabupaten_kota', 100)->nullable();
            $t->string('provinsi', 100)->nullable(); $t->string('kode_pos', 10)->nullable(); $t->string('pendidikan_terakhir', 50)->nullable();
            $t->string('pekerjaan', 100)->nullable(); $t->string('nama_instansi', 150)->nullable(); $t->string('jabatan', 100)->nullable(); $t->string('penghasilan', 50)->nullable();
            $t->timestamps(); $t->unique(['siswa_id','jenis']);
        });
        Schema::create('wali_siswa', function (Blueprint $t) {
            $t->id(); $t->foreignId('siswa_id')->unique()->constrained('siswa')->cascadeOnDelete();
            $t->enum('sumber_wali', ['Ayah','Ibu','Orang lain'])->nullable(); $t->string('nama_lengkap', 200)->nullable(); $t->string('nik', 16)->nullable();
            $t->string('hubungan_dengan_siswa', 100)->nullable(); $t->string('tempat_lahir', 100)->nullable(); $t->date('tanggal_lahir')->nullable();
            $t->string('no_hp', 20)->nullable(); $t->string('no_whatsapp', 20)->nullable(); $t->text('alamat')->nullable();
            $t->string('pendidikan_terakhir', 50)->nullable(); $t->string('pekerjaan', 100)->nullable(); $t->string('penghasilan', 50)->nullable(); $t->timestamps();
        });
        Schema::create('lampiran_siswa', function (Blueprint $t) {
            $t->id(); $t->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete(); $t->string('jenis_dokumen', 50);
            $t->string('path'); $t->string('nama_asli'); $t->string('mime_type', 100)->nullable(); $t->unsignedBigInteger('ukuran')->nullable(); $t->timestamps();
            $t->unique(['siswa_id','jenis_dokumen']);
        });
    }
    public function down(): void { Schema::dropIfExists('lampiran_siswa'); Schema::dropIfExists('wali_siswa'); Schema::dropIfExists('data_orang_tua_siswa'); }
};
