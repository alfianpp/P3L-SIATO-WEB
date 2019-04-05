<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPenjualanSparepartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_penjualan_spareparts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_detail_penjualan');
            $table->foreign('id_detail_penjualan')->references('id')->on('detail_penjualan');
            $table->string('kode_spareparts', 12);
            $table->foreign('kode_spareparts')->references('kode')->on('spareparts');
            $table->integer('jumlah');
            $table->double('harga', 11, 2);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detail_penjualan_spareparts');
    }
}
