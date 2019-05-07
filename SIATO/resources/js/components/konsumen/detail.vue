<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Detail Riwayat Transaksi {{ nomorTransaksi }}</h1>
        </section>
        
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <b>Konsumen</b><br>
                                    <b>Telepon</b>
                                </div>

                                <div class="col-sm-8" v-if="penjualan != null">
                                    <!-- {{ penjualan.konsumen.nama }}<br>
                                    {{ penjualan.konsumen.nomor_telepon }}<br> -->
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <b>Tanggal Transaksi</b><br>
                                    <b>Status</b>
                                </div>

                                <div class="col-sm-8" v-if="penjualan != null">
                                    <!-- {{ penjualan.tgl_transaksi }}<br>
                                    {{ statusTransaksi }} -->
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div v-for="(detail_penjualan, index) in detailPenjualan" v-bind:key="index" class="box">
                        <div class="box-body">
                            <div v-if="penjualan != null && penjualan.jenis === 'SV' || penjualan.jenis === 'SS'" class="row" style="margin-bottom: 15px;">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <b>Motor</b><br>
                                            <b>Montir</b><br>
                                        </div>

                                        <div class="col-sm-8">
                                            <!-- {{ detail_penjualan.kendaraan.merk }} {{ detail_penjualan.kendaraan.tipe }} {{ detail_penjualan.kendaraan.nomor_polisi }} <br>
                                            {{ detail_penjualan.montir.nama }} <br> -->
                                        </div>
                                    </div>
                                </div> 
                            </div>

                            <div class="row">
                                <div v-if="penjualan != null && penjualan.jenis === 'SP' || penjualan.jenis === 'SS'" :class="sparepartsClass">
                                    <detail-spareparts 
                                    v-bind:index="index+1"
                                    v-bind:id-detail-penjualan="detail_penjualan.id">
                                    </detail-spareparts>
                                </div>

                                <div v-if="penjualan != null && penjualan.jenis === 'SV' || penjualan.jenis === 'SS'" :class="jasaserviceClass">
                                    <detail-jasa-service
                                    v-bind:index="index+1"
                                    v-bind:id-detail-penjualan="detail_penjualan.id">
                                    </detail-jasa-service>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <form-tambah-ubah-detail-penjualan 
        v-if="showFormTambahUbah == true" 
        v-bind:id-penjualan="idPenjualan"
        v-bind:id-konsumen="penjualan.konsumen.id" 
        v-bind:form-action="formAction" 
        v-bind:selected-detail-penjualan="selectedDetailPenjualan" 
        v-on:close="closeForm">
        </form-tambah-ubah-detail-penjualan>
    </div>
</template>

<script>

</script>