<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Hanya menu dengan route yang benar-benar ada di routes/web.php
        $menus = [
            ['id' => 1, 'id_parent' => 0, 'title' => 'Dashboard', 'route_name' => 'dashboard', 'icon' => 'bi bi-grid-fill', 'urutan' => 1, 'lihat' => 1, 'tambah' => 0, 'edit' => 0, 'hapus' => 0],
            ['id' => 2, 'id_parent' => 0, 'title' => 'Master', 'route_name' => '#', 'icon' => 'bi bi-stack', 'urutan' => 14, 'lihat' => 1, 'tambah' => 0, 'edit' => 0, 'hapus' => 0],
            ['id' => 6, 'id_parent' => 2, 'title' => 'Data Kelas', 'route_name' => 'kelas.index', 'icon' => 'bi bi-building', 'urutan' => 4, 'lihat' => 1, 'tambah' => 1, 'edit' => 1, 'hapus' => 1],
            ['id' => 7, 'id_parent' => 2, 'title' => 'Tahun Ajaran', 'route_name' => 'tahun-ajaran.index', 'icon' => '', 'urutan' => 5, 'lihat' => 1, 'tambah' => 1, 'edit' => 1, 'hapus' => 1],
            ['id' => 27, 'id_parent' => 0, 'title' => 'Pengaturan', 'route_name' => '#', 'icon' => 'bi bi-gear-fill', 'urutan' => 15, 'lihat' => 1, 'tambah' => 0, 'edit' => 0, 'hapus' => 0],
            ['id' => 28, 'id_parent' => 27, 'title' => 'Pengguna', 'route_name' => 'pengguna.index', 'icon' => 'bi bi-person', 'urutan' => 1, 'lihat' => 1, 'tambah' => 1, 'edit' => 1, 'hapus' => 1],
            ['id' => 29, 'id_parent' => 27, 'title' => 'Hak Akses', 'route_name' => 'hak-akses.index', 'icon' => 'bi bi-gear', 'urutan' => 2, 'lihat' => 1, 'tambah' => 0, 'edit' => 1, 'hapus' => 0],
            ['id' => 35, 'id_parent' => 0, 'title' => 'Siswa', 'route_name' => '#', 'icon' => 'bi bi-people', 'urutan' => 5, 'lihat' => 1, 'tambah' => 0, 'edit' => 0, 'hapus' => 0],
            ['id' => 36, 'id_parent' => 35, 'title' => 'Data Siswa', 'route_name' => 'siswa.index', 'icon' => 'bi bi-database', 'urutan' => 1, 'lihat' => 1, 'tambah' => 1, 'edit' => 1, 'hapus' => 1],
            ['id' => 37, 'id_parent' => 35, 'title' => 'Calon Siswa', 'route_name' => 'calon-siswa.index', 'icon' => '', 'urutan' => 2, 'lihat' => 1, 'tambah' => 0, 'edit' => 0, 'hapus' => 0],
        ];

        foreach ($menus as $menu) {
            DB::table('menu')->updateOrInsert(['id' => $menu['id']], $menu);
        }
    }
}
