<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_pendaftaran', function (Blueprint $table) {
            $table->string('token', 10)->nullable()->unique();
            $table->string('status', 20)->default('formulir');
            $table->foreignId('siswa_id')->nullable()->unique()->constrained('siswa')->nullOnDelete();
            $table->timestamp('wa_dikirim_at')->nullable();
            $table->timestamp('selesai_at')->nullable();
        });
        DB::table('spmb_pendaftaran')->orderBy('id')->each(function ($row) {
            do {
                $token = Str::random(10);
            } while (DB::table('spmb_pendaftaran')->where('token', $token)->exists());
            DB::table('spmb_pendaftaran')->where('id', $row->id)->update(['token' => $token]);
        });
        Schema::table('spmb_pendaftaran', fn (Blueprint $table) => $table->string('token', 10)->nullable(false)->change());
    }

    public function down(): void
    {
        Schema::table('spmb_pendaftaran', function (Blueprint $table) {
            $table->dropForeign(['siswa_id']);
            $table->dropUnique(['siswa_id']);
            $table->dropUnique(['token']);
            $table->dropColumn(['token', 'status', 'siswa_id', 'wa_dikirim_at', 'selesai_at']);
        });
    }
};
