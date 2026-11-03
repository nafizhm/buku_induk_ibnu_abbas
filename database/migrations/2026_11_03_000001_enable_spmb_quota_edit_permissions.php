<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::transaction(function () {
            $menuIds = DB::table('menu')->whereIn('route_name', ['admin.spmb.index', 'admin.smp.spmb.index'])->pluck('id');
            DB::table('menu')->whereIn('id', $menuIds)->update(['edit' => 1]);

            $admins = DB::table('users')->join('role', 'role.id', '=', 'users.id_role')
                ->where('role.role', 'Admin Sekolah')->pluck('users.id');
            DB::table('hak_akses')->whereIn('id_menu', $menuIds)->whereIn('id_user', $admins)
                ->where('lihat', 1)->update(['edit' => 1]);
        });
    }

    public function down(): void
    {
        // Preserve user permissions that may have been edited since this migration.
        DB::table('menu')->whereIn('route_name', ['admin.spmb.index', 'admin.smp.spmb.index'])->update(['edit' => 0]);
    }
};
