<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $roleId = DB::table('role')->where('role', 'Admin Sekolah')->value('id');

        $userId = DB::table('users')->where('username', 'admin')->value('id');

        if ($userId) {
            DB::table('users')->where('id', $userId)->update([
                'nama' => 'Administrator',
                'password' => Hash::make('password123'),
                'email' => 'admin@sekolah.local',
                'id_role' => $roleId,
                'status' => 'AKTIF',
                'is_active' => 1,
            ]);
        } else {
            $userId = DB::table('users')->insertGetId([
                'nama' => 'Administrator',
                'username' => 'admin',
                'password' => Hash::make('password123'),
                'email' => 'admin@sekolah.local',
                'id_role' => $roleId,
                'status' => 'AKTIF',
                'is_active' => 1,
                'id_guru' => 0,
                'id_siswa' => 0,
            ]);
        }

        $menus = DB::table('menu')->get();
        foreach ($menus as $menu) {
            DB::table('hak_akses')->updateOrInsert(
                ['id_user' => $userId, 'id_menu' => $menu->id],
                ['lihat' => 1, 'beranda' => 1, 'tambah' => 1, 'edit' => 1, 'hapus' => 1]
            );
        }
    }
}
