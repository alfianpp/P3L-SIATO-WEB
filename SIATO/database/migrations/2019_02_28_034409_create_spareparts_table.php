<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSparepartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('spareparts', function (Blueprint $table) {
            $table->string('kode', 12)->primary();
            $table->string('nama', 64);
            $table->string('merk', 32);
            $table->string('tipe', 32);
            $table->string('kode_peletakan', 12);
            $table->double('harga_beli', 11, 2);
            $table->double('harga_jual', 11, 2);
            $table->integer('stok');
            $table->integer('stok_minimal');
            $table->text('url_gambar')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('spareparts');
    }
}
