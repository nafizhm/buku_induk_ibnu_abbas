<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Kedua tabel (data_orang_tua_siswa & wali_siswa) sudah digabung ke tabel orang_tua
// lewat migration 2026_08_24_000300, sehingga ALTER di migration ini tidak diperlukan lagi.
return new class extends Migration {
    public function up(): void
    {
        //
    }

    public function down(): void
    {
        //
    }
};
