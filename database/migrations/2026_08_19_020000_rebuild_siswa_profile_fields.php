<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement('ALTER TABLE siswa MODIFY rfid BIGINT NULL');
        DB::statement('ALTER TABLE siswa MODIFY pin_rfid VARCHAR(10) NULL');
        DB::statement('ALTER TABLE siswa MODIFY jarak_sekolah DECIMAL(8,2) NULL');
        Schema::table('siswa', function (Blueprint $t) {
            $t->string('nik', 16)->nullable()->unique()->after('nisn'); $t->string('no_kk', 16)->nullable()->after('nik');
            $t->unsignedSmallInteger('anak_ke')->nullable(); $t->unsignedSmallInteger('jumlah_saudara_kandung')->nullable();
            $t->unsignedSmallInteger('jumlah_saudara_tiri')->nullable(); $t->unsignedSmallInteger('jumlah_saudara_angkat')->nullable();
            $t->string('status_anak', 20)->nullable(); $t->string('status_dalam_keluarga', 100)->nullable();
            $t->string('tahun_ajaran_masuk', 20)->nullable(); $t->date('tanggal_masuk_sekolah')->nullable();
            $t->string('kelas_saat_masuk', 50)->nullable(); $t->string('status_siswa', 20)->default('Aktif');
            $t->string('npsn_sekolah_asal', 20)->nullable(); $t->string('no_ijazah_sebelumnya', 100)->nullable(); $t->string('no_skhun_sttb', 100)->nullable();
            $t->string('rt', 5)->nullable(); $t->string('rw', 5)->nullable(); $t->string('dusun', 100)->nullable();
            $t->string('desa_kelurahan', 100)->nullable(); $t->string('kecamatan', 100)->nullable();
            $t->string('kabupaten_kota', 100)->nullable(); $t->string('provinsi', 100)->nullable(); $t->string('kode_pos', 10)->nullable();
            $t->string('status_tempat_tinggal', 30)->nullable(); $t->string('moda_transportasi', 100)->nullable(); $t->string('no_hp_darurat', 20)->nullable();
            $t->decimal('tinggi_badan', 6, 2)->nullable(); $t->decimal('berat_badan', 6, 2)->nullable(); $t->decimal('lingkar_kepala', 6, 2)->nullable();
            $t->boolean('berkebutuhan_khusus')->default(false); $t->string('jenis_kebutuhan_khusus')->nullable(); $t->text('riwayat_kesehatan')->nullable();
        });
    }
    public function down(): void {}
};
