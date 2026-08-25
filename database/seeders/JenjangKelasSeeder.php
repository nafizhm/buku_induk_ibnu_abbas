<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenjangKelasSeeder extends Seeder
{
    public function run(): void
    {
        foreach (['7', '8', '9'] as $jenjang) {
            DB::table('jenjang_kelas')->updateOrInsert(['jenjang' => $jenjang], ['jenjang' => $jenjang]);
        }
    }
}
