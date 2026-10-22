<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('menu')) {
            Schema::create('menu', function (Blueprint $table) {
                $table->increments('id');
                $table->string('nama_menu', 100);
                $table->integer('harga');
                $table->string('kategori', 50);
            });
        }

        if (! Schema::hasTable('pesanan')) {
            Schema::create('pesanan', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('user_id');
                $table->unsignedInteger('menu_id');
                $table->integer('jumlah');
                $table->integer('total_harga');
                $table->string('status', 100)->default('Menunggu Konfirmasi');
            });
        }
    }

    public function down(): void
    {
        // These tables may be legacy tables with existing kantin data.
    }
};
