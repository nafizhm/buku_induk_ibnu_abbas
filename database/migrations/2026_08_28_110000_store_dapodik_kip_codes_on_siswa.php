<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('siswa', function (Blueprint $table) {
            $table->string('punya_kip', 30)->nullable()->change();
            $table->string('terima_kip', 30)->nullable()->change();
        });
        DB::table('siswa')->where('punya_kip', '1')->update(['punya_kip' => '01) Ya']);
        DB::table('siswa')->where('punya_kip', '0')->update(['punya_kip' => '02) Tidak']);
        DB::table('siswa')->where('terima_kip', '1')->update(['terima_kip' => '01) Ya']);
        DB::table('siswa')->where('terima_kip', '0')->update(['terima_kip' => '02) Tidak']);
    }

    public function down(): void
    {
        DB::table('siswa')->where('punya_kip', '01) Ya')->update(['punya_kip' => 1]);
        DB::table('siswa')->where('punya_kip', '02) Tidak')->update(['punya_kip' => 0]);
        DB::table('siswa')->where('terima_kip', '01) Ya')->update(['terima_kip' => 1]);
        DB::table('siswa')->where('terima_kip', '02) Tidak')->update(['terima_kip' => 0]);
        Schema::table('siswa', function (Blueprint $table) {
            $table->boolean('punya_kip')->nullable()->change();
            $table->boolean('terima_kip')->nullable()->change();
        });
    }
};
