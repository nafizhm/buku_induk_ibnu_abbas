<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['Kepala Sekolah', 'Guru', 'Siswa', 'Admin Sekolah', 'Wali Murid', 'Orang Tua'];

        foreach ($roles as $role) {
            DB::table('role')->updateOrInsert(['role' => $role], ['role' => $role]);
        }
    }
}
