<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('spmb_pendaftaran', function (Blueprint $table) {
            $table->json('pernyataan_data')->nullable();
            $table->timestamp('pernyataan_at')->nullable();
            $table->json('wawancara_data')->nullable();
            $table->timestamp('wawancara_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('spmb_pendaftaran', fn (Blueprint $table) => $table->dropColumn(['pernyataan_data', 'pernyataan_at', 'wawancara_data', 'wawancara_at']));
    }
};
