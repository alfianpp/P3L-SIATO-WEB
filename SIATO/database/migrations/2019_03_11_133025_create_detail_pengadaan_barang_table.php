<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPengadaanBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_pengadaan_barang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_pengadaan_barang');
            $table->foreign('id_pengadaan_barang')->references('id')->on('pengadaan_barang');
            $table->string('kode_spareparts', 12);
            $table->foreign('kode_spareparts')->references('kode')->on('spareparts');
            $table->integer('jumlah_pesan');
            $table->integer('jumlah_datang')->nullable();
            $table->double('harga', 11, 2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_pengadaan_barang');
    }
}
