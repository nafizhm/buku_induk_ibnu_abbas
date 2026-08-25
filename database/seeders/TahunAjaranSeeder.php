<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TahunAjaranSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('tahun_ajaran')->updateOrInsert(
            ['tahun' => '2025/2026'],
            ['tahun' => '2025/2026', 'is_active' => 1]
        );
    }
}
