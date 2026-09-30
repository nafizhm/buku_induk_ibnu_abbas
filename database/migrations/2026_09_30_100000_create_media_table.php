<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->string('jenis', 10);
            $table->string('kategori', 3);
            $table->string('judul', 150);
            $table->string('path')->nullable();
            $table->string('youtube_code', 11)->nullable();
            $table->timestamps();
            $table->index(['kategori', 'jenis']);
        });
        $galleries = [
            'SD' => [4 => 'Kebersamaan santriwati di sekolah', 5 => 'Kegiatan belajar di kelas', 6 => 'Belajar Al-Quran bersama', 7 => 'Pendampingan membaca Al-Quran', 8 => 'Suasana belajar santriwati', 3 => 'Ruang ibadah dan belajar', 9 => 'Ruang administrasi sekolah'],
            'SMP' => [1 => 'Kebersamaan santri di pantai', 2 => 'Kegiatan santri di Kilang Mandiri', 3 => 'Ruang ibadah dan belajar', 9 => 'Ruang administrasi sekolah', 10 => 'Asrama santri'],
        ];
        foreach ($galleries as $kategori => $photos) {
            foreach ($photos as $number => $judul) {
                DB::table('media')->insert(['jenis' => 'foto', 'kategori' => $kategori, 'judul' => $judul,
                    'path' => 'assets/spmb/galeri/foto-'.$number.'.jpeg', 'created_at' => now(), 'updated_at' => now()]);
            }
            DB::table('media')->insert(['jenis' => 'video', 'kategori' => $kategori, 'judul' => "Video Rumah Qur'an Ibnu Abbas",
                'youtube_code' => '5NSZyhx1s-w', 'created_at' => now(), 'updated_at' => now()]);
        }
        $order = (int) DB::table('menu')->max('urutan') + 1;
        $parent = DB::table('menu')->insertGetId(['id_parent' => 0, 'title' => 'Media', 'route_name' => 'media-group',
            'icon' => 'bi bi-images', 'urutan' => $order, 'lihat' => 1, 'tambah' => 0, 'edit' => 0, 'hapus' => 0]);
        $ids = [$parent];
        foreach (['foto' => 'Foto', 'video' => 'Video'] as $jenis => $title) {
            $ids[] = DB::table('menu')->insertGetId(['id_parent' => $parent, 'title' => $title,
                'route_name' => 'admin.media.'.$jenis.'.index', 'icon' => 'bi bi-'.($jenis === 'foto' ? 'image' : 'play-btn'),
                'urutan' => ++$order, 'lihat' => 1, 'tambah' => 1, 'edit' => 1, 'hapus' => 1]);
        }
        $admins = DB::table('users')->join('role', 'role.id', '=', 'users.id_role')->where('role.role', 'Admin Sekolah')->pluck('users.id');
        foreach ($admins as $user) {
            foreach ($ids as $id) {
                DB::table('hak_akses')->insert(['id_user' => $user, 'id_menu' => $id, 'beranda' => 0,
                    'lihat' => 1, 'tambah' => $id === $parent ? 0 : 1, 'edit' => $id === $parent ? 0 : 1, 'hapus' => $id === $parent ? 0 : 1]);
            }
        }
    }

    public function down(): void
    {
        $ids = DB::table('menu')->whereIn('route_name', ['media-group', 'admin.media.foto.index', 'admin.media.video.index'])->pluck('id');
        DB::table('hak_akses')->whereIn('id_menu', $ids)->delete();
        DB::table('menu')->whereIn('id', $ids)->delete();
        Schema::dropIfExists('media');
    }
};
