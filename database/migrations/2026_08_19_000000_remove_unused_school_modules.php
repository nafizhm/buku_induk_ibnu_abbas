<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Remove copied modules that are not part of Buku Induk.
     *
     * This migration intentionally removes module data permanently. Foreign-key
     * checks are disabled because the source database contains cross-module
     * relations which will be rebuilt later as the retained modules are tidied.
     */
    public function up(): void
    {
        $rootTitles = [
            'LMS Guru',
            'Presensi Guru',
            'Presensi Siswa',
            'Capaian Kinerja',
            'Ijazah',
            'Informasi Akademik',
            'Akademik',
        ];

        if (Schema::hasTable('menu')) {
            $menuIds = DB::table('menu')
                ->whereIn('title', $rootTitles)
                ->pluck('id')
                ->all();

            do {
                $childIds = DB::table('menu')
                    ->whereIn('id_parent', $menuIds)
                    ->whereNotIn('id', $menuIds)
                    ->pluck('id')
                    ->all();

                $newIds = array_values(array_diff($childIds, $menuIds));
                $menuIds = array_values(array_unique(array_merge($menuIds, $newIds)));
            } while ($newIds !== []);

            if ($menuIds !== []) {
                foreach (['hak_akses', 'role_menu'] as $pivotTable) {
                    if (Schema::hasTable($pivotTable)) {
                        $column = $pivotTable === 'hak_akses' ? 'id_menu' : 'menu_id';

                        if (Schema::hasColumn($pivotTable, $column)) {
                            DB::table($pivotTable)->whereIn($column, $menuIds)->delete();
                        }
                    }
                }

                DB::table('menu')->whereIn('id', $menuIds)->delete();
            }
        }

        Schema::disableForeignKeyConstraints();

        foreach ([
            // Capaian kinerja
            'detail_capaian_rkg',
            'detail_capaian_sa',
            'detail_jawaban_rkg',
            'detail_jawaban_sa',
            'capaian_rkg',
            'capaian_sa',
            'soal_rkg',
            'soal_sa',

            // LMS guru
            'detail_jawaban_isi_soal',
            'isi_soal',
            'rekap_nilai',
            'tugas',
            'soal',

            // Presensi guru dan siswa
            'log_pesan',
            'rekap_keterlambatan',
            'izin_siswa',
            'absensi',
            'jam_kegiatan_khusus',
            'jam_kegiatan',

            // Ijazah
            'kelulusan_siswa',

            // Informasi Akademik dan Akademik
            'jadwal_mapel',
            'kalender_akademik',
            'mapel_rombel',
            'nilai_siswa',
            'mata_pelajaran',
            'jam_pelajaran',
            'pengajar',
        ] as $table) {
            Schema::dropIfExists($table);
        }

        Schema::enableForeignKeyConstraints();
    }

    public function down(): void
    {
        // The removed legacy schemas and their data cannot be reconstructed safely.
    }
};
