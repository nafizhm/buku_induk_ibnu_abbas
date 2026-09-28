<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_pendaftaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('jk', 30);
            $table->string('ortu');
            $table->string('wa', 25);
            $table->string('bukti_path');
            $table->string('bukti_mime', 50);
            $table->timestamps();
        });

        $menuId = DB::table('menu')->insertGetId([
            'id_parent' => 0, 'title' => 'SPMB', 'route_name' => 'admin.spmb.index',
            'icon' => 'bi bi-person-plus-fill',
            'urutan' => (int) DB::table('menu')->max('urutan') + 1,
            'lihat' => 1, 'tambah' => 0, 'edit' => 0, 'hapus' => 0,
        ]);
        $admins = DB::table('users')->join('role', 'role.id', '=', 'users.id_role')
            ->where('role.role', 'Admin Sekolah')->pluck('users.id');
        foreach ($admins as $id) {
            DB::table('hak_akses')->insert([
                'id_user' => $id, 'id_menu' => $menuId, 'lihat' => 1,
                'beranda' => 0, 'tambah' => 0, 'edit' => 0, 'hapus' => 0,
            ]);
        }
    }

    public function down(): void
    {
        $ids = DB::table('menu')->where('route_name', 'admin.spmb.index')->pluck('id');
        DB::table('hak_akses')->whereIn('id_menu', $ids)->delete();
        DB::table('menu')->whereIn('id', $ids)->delete();
        Schema::dropIfExists('spmb_pendaftaran');
    }
};
