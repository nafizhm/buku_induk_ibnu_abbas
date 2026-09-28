<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('spmb_lampiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spmb_pendaftaran_id')->constrained('spmb_pendaftaran')->cascadeOnDelete();
            $table->string('jenis', 40);
            $table->string('path');
            $table->string('nama_asli');
            $table->string('mime', 100);
            $table->unsignedBigInteger('ukuran');
            $table->timestamps();
            $table->unique(['spmb_pendaftaran_id', 'jenis']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('spmb_lampiran');
    }
};
