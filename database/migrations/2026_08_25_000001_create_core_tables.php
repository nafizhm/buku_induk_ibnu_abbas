<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('role', function (Blueprint $table) {
            $table->increments('id');
            $table->string('role');
        });

        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_parent');
            $table->string('title');
            $table->string('route_name');
            $table->string('icon');
            $table->integer('urutan');
            $table->integer('lihat');
            $table->integer('tambah');
            $table->integer('edit');
            $table->integer('hapus');
        });

        Schema::create('hak_akses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_user');
            $table->integer('id_menu');
            $table->integer('lihat');
            $table->integer('beranda')->default(0);
            $table->integer('tambah');
            $table->integer('edit');
            $table->integer('hapus');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hak_akses');
        Schema::dropIfExists('menu');
        Schema::dropIfExists('role');
    }
};
