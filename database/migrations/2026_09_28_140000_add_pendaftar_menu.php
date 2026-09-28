<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $id = DB::table('menu')->insertGetId([
            'id_parent' => 0, 'title' => 'Pendaftar', 'route_name' => 'admin.pendaftar.index',
            'icon' => 'bi bi-person-lines-fill', 'urutan' => (int) DB::table('menu')->max('urutan') + 1,
            'lihat' => 1, 'tambah' => 0, 'edit' => 0, 'hapus' => 0,
        ]);
        $users = DB::table('hak_akses')->join('menu', 'menu.id', '=', 'hak_akses.id_menu')
            ->where('menu.route_name', 'admin.spmb.index')->where('hak_akses.lihat', 1)->distinct()->pluck('id_user');
        foreach ($users as $user) {
            DB::table('hak_akses')->insert([
                'id_user' => $user, 'id_menu' => $id, 'lihat' => 1,
                'beranda' => 0, 'tambah' => 0, 'edit' => 0, 'hapus' => 0,
            ]);
        }
    }

    public function down(): void
    {
        $ids = DB::table('menu')->where('route_name', 'admin.pendaftar.index')->pluck('id');
        DB::table('hak_akses')->whereIn('id_menu', $ids)->delete();
        DB::table('menu')->whereIn('id', $ids)->delete();
    }
};
