<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriBarangTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('histori_barang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('kode_spareparts', 12);
            $table->foreign('kode_spareparts')->references('kode')->on('spareparts');
            $table->integer('keluar');
            $table->integer('masuk');
            $table->timestamp('tgl_transaksi')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('histori_barangg');
    }
}
