<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nipd', 20)->nullable();
            $table->string('nisn', 25)->nullable();
            $table->string('nik', 16)->nullable()->unique();
            $table->string('no_kk', 16)->nullable();
            $table->string('no_akta', 100)->nullable();
            $table->bigInteger('rfid')->nullable();
            $table->string('pin_rfid', 10)->nullable();
            $table->bigInteger('saldo')->default(0);
            $table->string('nfc_decimal')->nullable()->unique();
            $table->string('nfc_hex')->nullable()->unique();
            $table->string('password', 100)->nullable();
            $table->string('nama_lengkap', 200);
            $table->string('nama_panggilan', 100)->nullable();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 100);
            $table->date('tanggal_lahir');
            $table->string('agama', 50)->nullable();
            $table->string('kewarganegaraan', 50)->nullable();
            $table->string('nama_negara', 100)->nullable();
            $table->integer('jumlah_saudara')->nullable();
            $table->string('pekerjaan', 100)->nullable();
            $table->boolean('punya_kip')->nullable();
            $table->boolean('terima_kip')->nullable();
            $table->string('alasan_tolak_pip', 100)->nullable();
            $table->string('bahasa_rumah', 100)->nullable();
            $table->string('golongan_darah', 5)->nullable();
            $table->text('alamat')->nullable();
            $table->string('no_telepon_rumah', 20)->nullable();
            $table->string('email', 191)->nullable();
            $table->string('no_hp', 20)->nullable();
            $table->string('tinggal_dengan', 50)->nullable();
            $table->decimal('jarak_sekolah', 8, 2)->nullable();
            $table->decimal('jarak_tempuh', 8, 2)->nullable();
            $table->smallInteger('waktu_jam')->unsigned()->nullable();
            $table->smallInteger('waktu_menit')->unsigned()->nullable();
            $table->string('foto', 100)->nullable();
            $table->integer('kesanggupan_spp')->default(0);
            $table->integer('semester')->default(0);
            $table->integer('target')->default(0);
            $table->timestamps();
            $table->smallInteger('anak_ke')->unsigned()->nullable();
            $table->smallInteger('jumlah_saudara_kandung')->unsigned()->nullable();
            $table->smallInteger('jumlah_saudara_tiri')->unsigned()->nullable();
            $table->smallInteger('jumlah_saudara_angkat')->unsigned()->nullable();
            $table->string('status_anak', 20)->nullable();
            $table->string('status_dalam_keluarga', 100)->nullable();
            $table->string('tahun_ajaran_masuk', 20)->nullable();
            $table->date('tanggal_masuk_sekolah')->nullable();
            $table->string('sekolah_asal', 191)->nullable();
            $table->string('no_peserta_un', 100)->nullable();
            $table->string('no_seri_ijazah', 100)->nullable();
            $table->string('no_skhun', 100)->nullable();
            $table->string('keluar_karena', 50)->nullable();
            $table->date('tanggal_keluar')->nullable();
            $table->text('alasan_keluar')->nullable();
            $table->string('kelas_saat_masuk', 50)->nullable();
            $table->unsignedInteger('kelas_id')->nullable();
            $table->foreign('kelas_id')->references('id_kelas')->on('kelas')->nullOnDelete();
            $table->string('status_siswa', 20)->default('Aktif');
            $table->string('npsn_sekolah_asal', 20)->nullable();
            $table->string('no_ijazah_sebelumnya', 100)->nullable();
            $table->string('no_skhun_sttb', 100)->nullable();
            $table->string('rt', 5)->nullable();
            $table->string('rw', 5)->nullable();
            $table->string('dusun', 100)->nullable();
            $table->string('desa_kelurahan', 100)->nullable();
            $table->string('kecamatan', 100)->nullable();
            $table->string('kabupaten_kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->decimal('lintang', 10, 6)->nullable();
            $table->decimal('bujur', 10, 6)->nullable();
            $table->string('status_tempat_tinggal', 30)->nullable();
            $table->string('moda_transportasi', 100)->nullable();
            $table->string('no_hp_darurat', 20)->nullable();
            $table->decimal('tinggi_badan', 6, 2)->nullable();
            $table->decimal('berat_badan', 6, 2)->nullable();
            $table->decimal('lingkar_kepala', 6, 2)->nullable();
            $table->string('jenis_kesejahteraan', 50)->nullable();
            $table->string('no_kartu', 100)->nullable();
            $table->string('nama_di_kartu', 191)->nullable();
            $table->string('kompetensi_keahlian', 100)->nullable();
            $table->string('jenis_pendaftaran', 50)->nullable();
            $table->string('nis', 50)->nullable();
            $table->boolean('berkebutuhan_khusus')->default(false);
            $table->string('jenis_kebutuhan_khusus')->nullable();
            $table->text('riwayat_kesehatan')->nullable();
        });

        Schema::create('orang_tua', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('nama_ayah', 200)->nullable();
            $table->string('nik_ayah', 16)->nullable();
            $table->year('tahun_lahir_ayah')->nullable();
            $table->string('no_telp_ayah', 20)->nullable();
            $table->string('pendidikan_ayah', 100)->nullable();
            $table->string('pekerjaan_ayah', 100)->nullable();
            $table->string('penghasilan_ayah', 50)->nullable();
            $table->string('berkebutuhan_ayah', 50)->nullable();
            $table->string('nama_ibu', 200)->nullable();
            $table->string('nik_ibu', 16)->nullable();
            $table->year('tahun_lahir_ibu')->nullable();
            $table->string('no_telp_ibu', 20)->nullable();
            $table->string('pendidikan_ibu', 100)->nullable();
            $table->string('pekerjaan_ibu', 100)->nullable();
            $table->string('penghasilan_ibu', 50)->nullable();
            $table->string('berkebutuhan_ibu', 50)->nullable();
            $table->string('nama_wali', 200)->nullable();
            $table->string('nik_wali', 16)->nullable();
            $table->year('tahun_lahir_wali')->nullable();
            $table->string('hubungan_wali', 100)->nullable();
            $table->string('pendidikan_wali', 100)->nullable();
            $table->string('pekerjaan_wali', 100)->nullable();
            $table->string('penghasilan_wali', 50)->nullable();
            $table->enum('whatsapp_target', ['ayah', 'ibu', 'wali'])->nullable();
            $table->string('fcm_token')->nullable();
            $table->timestamps();
        });

        Schema::create('lampiran_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('jenis_dokumen', 50);
            $table->string('path');
            $table->string('nama_asli');
            $table->string('mime_type', 100)->nullable();
            $table->unsignedBigInteger('ukuran')->nullable();
            $table->timestamps();
            $table->unique(['siswa_id', 'jenis_dokumen']);
        });

        Schema::create('prestasi_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('jenis', 100)->nullable();
            $table->string('tingkat', 50)->nullable();
            $table->string('nama', 191)->nullable();
            $table->year('tahun')->nullable();
            $table->string('penyelenggara', 191)->nullable();
            $table->timestamps();
        });

        Schema::create('beasiswa_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('jenis', 100)->nullable();
            $table->text('keterangan')->nullable();
            $table->year('tahun_mulai')->nullable();
            $table->year('tahun_selesai')->nullable();
            $table->timestamps();
        });

        Schema::create('akun_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->enum('hubungan', ['ayah', 'ibu', 'wali']);
            $table->timestamps();
            $table->unique(['user_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_siswa');
        Schema::dropIfExists('beasiswa_siswa');
        Schema::dropIfExists('prestasi_siswa');
        Schema::dropIfExists('lampiran_siswa');
        Schema::dropIfExists('orang_tua');
        Schema::dropIfExists('siswa');
    }
};
