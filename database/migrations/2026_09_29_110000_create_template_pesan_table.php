<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('template_pesan', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('penggunaan')->nullable()->unique();
            $table->text('isi');
            $table->timestamps();
        });
        DB::table('template_pesan')->insert([
            'nama' => 'Pengisian Formulir Awal SPMB', 'penggunaan' => 'formulir_awal',
            'isi' => "Assalamu'alaikum Bapak/Ibu [[ortu]],\n\nTerima kasih atas pendaftaran calon santri [[nama]]. Silakan lengkapi data registrasi dan berkas pendaftaran melalui link berikut:\n[[link]]\n\nSilakan buka Data Formulir dan Berkas / Lampiran, lalu lengkapi setiap bagian. Progres dapat dilihat melalui tautan yang sama. Surat Pernyataan akan menyusul.\n\nAdmin SPMB Rumah Qur'an Ibnu Abbas",
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $menu = DB::table('menu')->insertGetId([
            'id_parent' => 0, 'title' => 'Template', 'route_name' => 'admin.template.index',
            'icon' => 'bi bi-chat-left-text', 'urutan' => (int) DB::table('menu')->max('urutan') + 1,
            'lihat' => 1, 'tambah' => 1, 'edit' => 1, 'hapus' => 1,
        ]);
        $admins = DB::table('users')->join('role', 'role.id', '=', 'users.id_role')
            ->where('role.role', 'Admin Sekolah')->pluck('users.id');
        foreach ($admins as $id) {
            DB::table('hak_akses')->insert([
                'id_user' => $id, 'id_menu' => $menu, 'beranda' => 0,
                'lihat' => 1, 'tambah' => 1, 'edit' => 1, 'hapus' => 1,
            ]);
        }
    }

    public function down(): void
    {
        $ids = DB::table('menu')->where('route_name', 'admin.template.index')->pluck('id');
        DB::table('hak_akses')->whereIn('id_menu', $ids)->delete();
        DB::table('menu')->whereIn('id', $ids)->delete();
        Schema::dropIfExists('template_pesan');
    }
};
