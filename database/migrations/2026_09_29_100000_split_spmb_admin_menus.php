<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $sources = DB::table('menu')->whereIn('route_name', ['admin.spmb.index', 'admin.pendaftar.index'])->orderBy('urutan')->get();
            $order = $sources->min('urutan') ?? ((int) DB::table('menu')->max('urutan') + 1);
            foreach (['SD', 'SMP'] as $offset => $jenjang) {
                $parent = DB::table('menu')->insertGetId([
                    'id_parent' => 0, 'title' => 'SPMB '.$jenjang, 'route_name' => 'spmb-group-'.strtolower($jenjang),
                    'icon' => 'bi bi-person-plus-fill', 'urutan' => $order + $offset,
                    'lihat' => 1, 'tambah' => 0, 'edit' => 0, 'hapus' => 0,
                ]);
                foreach ($sources as $index => $source) {
                    $data = [
                        'id_parent' => $parent, 'title' => $source->route_name === 'admin.spmb.index' ? 'Formulir' : 'Pendaftar',
                        'route_name' => $jenjang === 'SD' ? $source->route_name : str_replace('admin.', 'admin.smp.', $source->route_name),
                        'icon' => $source->icon, 'urutan' => $index + 1,
                        'lihat' => 1, 'tambah' => 0, 'edit' => 0, 'hapus' => 0,
                    ];
                    if ($jenjang === 'SD') {
                        DB::table('menu')->where('id', $source->id)->update($data);
                        $child = $source->id;
                    } else {
                        $child = DB::table('menu')->insertGetId($data);
                    }
                    foreach (DB::table('hak_akses')->where('id_menu', $source->id)->get() as $access) {
                        $permissions = ['lihat' => $access->lihat, 'beranda' => 0, 'tambah' => 0, 'edit' => 0, 'hapus' => 0];
                        if ($jenjang === 'SMP') {
                            DB::table('hak_akses')->insert($permissions + ['id_user' => $access->id_user, 'id_menu' => $child]);
                        }
                        $existing = DB::table('hak_akses')->where('id_user', $access->id_user)->where('id_menu', $parent)->first();
                        $permissions['lihat'] = max($access->lihat, $existing->lihat ?? 0);
                        DB::table('hak_akses')->updateOrInsert(['id_user' => $access->id_user, 'id_menu' => $parent], $permissions);
                    }
                }
            }
        });
    }

    public function down(): void
    {
        DB::transaction(function () {
            foreach (['admin.spmb.index' => 'SPMB', 'admin.pendaftar.index' => 'Pendaftar'] as $route => $title) {
                $menu = DB::table('menu')->where('route_name', $route)->first();
                if ($menu) {
                    $parentOrder = DB::table('menu')->where('id', $menu->id_parent)->value('urutan') ?? $menu->urutan;
                    DB::table('menu')->where('id', $menu->id)->update(['id_parent' => 0, 'title' => $title, 'urutan' => $parentOrder + ($title === 'Pendaftar' ? 1 : 0)]);
                }
            }
            $ids = DB::table('menu')->whereIn('route_name', ['spmb-group-sd', 'spmb-group-smp', 'admin.smp.spmb.index', 'admin.smp.pendaftar.index'])->pluck('id');
            DB::table('hak_akses')->whereIn('id_menu', $ids)->delete();
            DB::table('menu')->whereIn('id', $ids)->delete();
        });
    }
};
