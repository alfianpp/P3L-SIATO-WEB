<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDetailPenjualanJasaServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_penjualan_jasaservice', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_detail_penjualan');
            $table->foreign('id_detail_penjualan')->references('id')->on('detail_penjualan');
            $table->unsignedBigInteger('id_jasaservice');
            $table->foreign('id_jasaservice')->references('id')->on('jasa_service');
            $table->integer('jumlah')->default(1);
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
        Schema::dropIfExists('detail_penjualan_jasa_service');
    }
}
