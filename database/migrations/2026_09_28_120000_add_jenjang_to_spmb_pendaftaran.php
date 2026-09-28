<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_pendaftaran', function (Blueprint $table) {
            $table->string('jenjang', 10)->default('SD')->after('jk');
        });
    }

    public function down(): void
    {
        Schema::table('spmb_pendaftaran', fn (Blueprint $table) => $table->dropColumn('jenjang'));
    }
};
