<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('menu')) {
            $menuIds = DB::table('menu')
                ->whereIn('title', ['Beranda', 'Rombongan Belajar', 'Libur Nasinonal', 'Libur Nasional'])
                ->pluck('id');

            if ($menuIds->isNotEmpty()) {
                if (Schema::hasTable('hak_akses')) {
                    DB::table('hak_akses')->whereIn('id_menu', $menuIds)->delete();
                }

                if (Schema::hasTable('role_menu')) {
                    DB::table('role_menu')->whereIn('menu_id', $menuIds)->delete();
                }

                DB::table('menu')->whereIn('id', $menuIds)->delete();
            }
        }

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('penempatan_siswa');
        Schema::dropIfExists('rombel');
        Schema::dropIfExists('hari_libur_nasional');
        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // Removed legacy schemas and data cannot be reconstructed safely.
    }
};
