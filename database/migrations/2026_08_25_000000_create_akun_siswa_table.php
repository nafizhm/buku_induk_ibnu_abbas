<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('akun_siswa', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $t->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $t->enum('hubungan', ['ayah', 'ibu', 'wali']);
            $t->timestamps();

            $t->unique(['user_id', 'siswa_id']);
        });

        if (Schema::hasTable('role') && ! DB::table('role')->where('role', 'Orang Tua')->exists()) {
            DB::table('role')->insert(['role' => 'Orang Tua']);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('akun_siswa');

        if (Schema::hasTable('role')) {
            DB::table('role')->where('role', 'Orang Tua')->delete();
        }
    }
};
