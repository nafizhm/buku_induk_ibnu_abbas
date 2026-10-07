<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_quota_locks', function (Blueprint $table) {
            $table->string('jenjang', 3);
            $table->string('jk', 30);
            $table->primary(['jenjang', 'jk']);
        });
        foreach (\App\Support\SpmbQuota::LIMITS as $jenjang => $categories) {
            foreach ($categories as $jk => $limit) {
                DB::table('spmb_quota_locks')->insert(compact('jenjang', 'jk'));
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('spmb_quota_locks');
    }
};
