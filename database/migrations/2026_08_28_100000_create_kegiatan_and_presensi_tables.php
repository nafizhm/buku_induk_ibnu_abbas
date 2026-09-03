<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kegiatan', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_kegiatan');
            $table->string('nama_kegiatan', 150);
            $table->enum('zona_waktu', ['WIB', 'WITA', 'WIT'])->default('WIB');
            $table->enum('status', ['aktif', 'non aktif'])->default('aktif');
            $table->timestamps();
        });

        Schema::create('presensi_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kegiatan_id')->constrained('kegiatan')->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->string('qr_code', 15)->unique();
            $table->string('jenis', 25)->default('undangan');
            $table->time('jam_kehadiran')->nullable();
            $table->time('jam_kehadiran_ayah')->nullable();
            $table->time('jam_kehadiran_ibu')->nullable();
            $table->dateTime('qr_diambil_at')->nullable();
            $table->timestamps();
            $table->unique(['kegiatan_id', 'siswa_id']);
        });

        $menuId = DB::table('menu')->insertGetId([
            'id_parent' => 0,
            'title' => 'Kegiatan',
            'route_name' => 'kegiatan.index',
            'icon' => 'bi bi-calendar-check',
            'urutan' => (int) DB::table('menu')->where('id_parent', 0)->max('urutan') + 1,
            'lihat' => 1, 'tambah' => 1, 'edit' => 1, 'hapus' => 1,
        ]);

        foreach (DB::table('users')->pluck('id') as $userId) {
            DB::table('hak_akses')->insert([
                'id_user' => $userId, 'id_menu' => $menuId,
                'lihat' => 1, 'beranda' => 0, 'tambah' => 1, 'edit' => 1, 'hapus' => 1,
            ]);
        }
    }

    public function down(): void
    {
        $menuIds = DB::table('menu')->where('route_name', 'kegiatan.index')->pluck('id');
        DB::table('hak_akses')->whereIn('id_menu', $menuIds)->delete();
        DB::table('menu')->whereIn('id', $menuIds)->delete();
        Schema::dropIfExists('presensi_kegiatan');
        Schema::dropIfExists('kegiatan');
    }
};
