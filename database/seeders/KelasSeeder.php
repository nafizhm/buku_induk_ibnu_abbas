<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KelasSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [];
        for ($i = 1; $i <= 6; $i++) {
            foreach (['BANIN' => 'banin', 'BANAT' => 'banat'] as $label => $jenis) {
                $rows[] = ['nama_kelas' => "$i $label", 'tingkat' => 'SD', 'jenis' => $jenis, 'status' => 'aktif', 'wali_kelas' => '', 'jadwal' => ''];
            }
        }
        foreach ([7, 8, 9] as $i) {
            $rows[] = ['nama_kelas' => "KELAS $i", 'tingkat' => 'SMP', 'jenis' => null, 'status' => 'aktif', 'wali_kelas' => '', 'jadwal' => ''];
        }

        foreach ($rows as $row) {
            DB::table('kelas')->updateOrInsert(['nama_kelas' => $row['nama_kelas']], $row);
        }
    }
}
