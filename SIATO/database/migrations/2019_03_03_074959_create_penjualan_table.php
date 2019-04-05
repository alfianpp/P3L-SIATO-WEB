<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePenjualanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('id_cabang');
            $table->foreign('id_cabang')->references('id')->on('cabang');
            $table->string('jenis', 2);
            $table->unsignedBigInteger('id_konsumen');
            $table->foreign('id_konsumen')->references('id')->on('konsumen');
            $table->double('subtotal', 11, 2)->nullable();
            $table->double('diskon', 11, 2)->nullable();
            $table->double('total', 11, 2)->nullable();
            $table->double('uang_diterima', 11, 2)->nullable();
            $table->unsignedBigInteger('id_cs');
            $table->foreign('id_cs')->references('id')->on('pegawai');
            $table->unsignedBigInteger('id_kasir')->nullable();
            $table->foreign('id_kasir')->references('id')->on('pegawai');
            $table->integer('status');
            $table->timestamp('tgl_transaksi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('penjualan');
    }
}
