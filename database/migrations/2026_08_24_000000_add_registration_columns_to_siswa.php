<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $t) {
            $t->string('no_akta', 100)->nullable()->after('no_kk');
            $t->string('nama_negara', 100)->nullable()->after('kewarganegaraan');
            $t->decimal('lintang', 10, 6)->nullable()->after('kode_pos');
            $t->decimal('bujur', 10, 6)->nullable()->after('lintang');
            $t->string('pekerjaan', 100)->nullable()->after('jumlah_saudara');
            $t->boolean('punya_kip')->nullable()->after('pekerjaan');
            $t->boolean('terima_kip')->nullable()->after('punya_kip');
            $t->string('alasan_tolak_pip', 100)->nullable()->after('terima_kip');
            $t->string('email', 191)->nullable()->after('no_telepon_rumah');
            $t->string('no_hp', 20)->nullable()->after('email');
            $t->decimal('jarak_tempuh', 8, 2)->nullable()->after('jarak_sekolah');
            $t->unsignedSmallInteger('waktu_jam')->nullable()->after('jarak_tempuh');
            $t->unsignedSmallInteger('waktu_menit')->nullable()->after('waktu_jam');
            $t->string('jenis_kesejahteraan', 50)->nullable()->after('lingkar_kepala');
            $t->string('no_kartu', 100)->nullable()->after('jenis_kesejahteraan');
            $t->string('nama_di_kartu', 191)->nullable()->after('no_kartu');
            $t->string('kompetensi_keahlian', 100)->nullable()->after('nama_di_kartu');
            $t->string('jenis_pendaftaran', 50)->nullable()->after('kompetensi_keahlian');
            $t->string('nis', 50)->nullable()->after('jenis_pendaftaran');
            $t->string('sekolah_asal', 191)->nullable()->after('tanggal_masuk_sekolah');
            $t->string('no_peserta_un', 100)->nullable()->after('sekolah_asal');
            $t->string('no_seri_ijazah', 100)->nullable()->after('no_peserta_un');
            $t->string('no_skhun', 100)->nullable()->after('no_seri_ijazah');
            $t->string('keluar_karena', 50)->nullable()->after('no_skhun');
            $t->date('tanggal_keluar')->nullable()->after('keluar_karena');
            $t->text('alasan_keluar')->nullable()->after('tanggal_keluar');
        });
    }

    public function down(): void
    {
        Schema::table('siswa', function (Blueprint $t) {
            $t->dropColumn([
                'no_akta', 'nama_negara', 'lintang', 'bujur', 'pekerjaan',
                'punya_kip', 'terima_kip', 'alasan_tolak_pip', 'email', 'no_hp',
                'jarak_tempuh', 'waktu_jam', 'waktu_menit', 'jenis_kesejahteraan',
                'no_kartu', 'nama_di_kartu', 'kompetensi_keahlian', 'jenis_pendaftaran',
                'nis', 'sekolah_asal', 'no_peserta_un', 'no_seri_ijazah', 'no_skhun',
                'keluar_karena', 'tanggal_keluar', 'alasan_keluar',
            ]);
        });
    }
};
