<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_quota_locks', fn (Blueprint $table) => $table->unsignedInteger('kuota')->default(0));
        foreach (\App\Support\SpmbQuota::LIMITS as $jenjang => $categories) {
            foreach ($categories as $jk => $limit) {
                DB::table('spmb_quota_locks')->where(compact('jenjang', 'jk'))->update(['kuota' => $limit]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('spmb_quota_locks', fn (Blueprint $table) => $table->dropColumn('kuota'));
    }
};
