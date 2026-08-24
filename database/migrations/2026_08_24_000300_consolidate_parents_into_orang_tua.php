<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('orang_tua', function (Blueprint $t) {
            $t->string('nik_ayah', 16)->nullable()->after('nama_ayah');
            $t->year('tahun_lahir_ayah')->nullable()->after('nik_ayah');
            $t->string('penghasilan_ayah', 50)->nullable()->after('pekerjaan_ayah');
            $t->string('berkebutuhan_ayah', 50)->nullable()->after('penghasilan_ayah');

            $t->string('nik_ibu', 16)->nullable()->after('nama_ibu');
            $t->year('tahun_lahir_ibu')->nullable()->after('nik_ibu');
            $t->string('penghasilan_ibu', 50)->nullable()->after('pekerjaan_ibu');
            $t->string('berkebutuhan_ibu', 50)->nullable()->after('penghasilan_ibu');

            $t->string('nik_wali', 16)->nullable()->after('nama_wali');
            $t->year('tahun_lahir_wali')->nullable()->after('nik_wali');
            $t->string('penghasilan_wali', 50)->nullable()->after('pekerjaan_wali');
        });

        Schema::dropIfExists('data_orang_tua_siswa');
        Schema::dropIfExists('wali_siswa');
    }

    public function down(): void
    {
        Schema::table('orang_tua', function (Blueprint $t) {
            $t->dropColumn([
                'nik_ayah', 'tahun_lahir_ayah', 'penghasilan_ayah', 'berkebutuhan_ayah',
                'nik_ibu', 'tahun_lahir_ibu', 'penghasilan_ibu', 'berkebutuhan_ibu',
                'nik_wali', 'tahun_lahir_wali', 'penghasilan_wali',
            ]);
        });
    }
};
