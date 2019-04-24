<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Detail Penjualan {{ nomorTransaksi }}</h1>
            <div v-if="penjualan != null" class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                <button v-if="penjualan.jenis === 'SV' || penjualan.jenis === 'SS'" @click="openForm('TAMBAH')" type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-plus"></i> Tambah</button>
            </div>
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
                                    {{ penjualan.konsumen.nama }}<br>
                                    {{ penjualan.konsumen.nomor_telepon }}<br>
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
                                    {{ penjualan.tgl_transaksi }}<br>
                                    {{ statusTransaksi }}
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
                                            {{ detail_penjualan.kendaraan.merk }} {{ detail_penjualan.kendaraan.tipe }} {{ detail_penjualan.kendaraan.nomor_polisi }} <br>
                                            {{ detail_penjualan.montir.nama }} <br>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="pull-right">
                                        <button @click="openForm('UBAH', index)" type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-pencil"></i> Ubah</button>
                                        <button @click="deleteDetailPenjualan(detail_penjualan.id)" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
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
import formTambahUbahDetailPenjualan from './form-tambah-ubah-detail.vue';
import detailSpareparts from './detail-spareparts.vue';
import detailJasaService from './detail-jasaservice.vue';
import moment from 'moment'

export default {
    components: {
      formTambahUbahDetailPenjualan, detailJasaService, detailSpareparts
    },
    props: ['idPenjualan'],
    data: function() {
        return {
            penjualan: null,
            detailPenjualan: null,
            formAction: null,
            selectedDetailPenjualan: null,
            showFormTambahUbah: false,
        }
    },
    methods: {
        getPenjualan() {
            axios.post(this.$root.app.url + 'api/transaksi/penjualan/data/' + this.idPenjualan, {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.penjualan = response.data.data
                }
            })
        },
        getDetailPenjualan() {
            axios.post(this.$root.app.url + 'api/transaksi/penjualan/detail/' + this.idPenjualan, {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.detailPenjualan = response.data.data
                }
            })
        },
        deleteDetailPenjualan(id) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus detail penjualan ini?")) {
                axios.delete(this.$root.app.url + 'api/transaksi/penjualan/detail/' + id, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getDetailPenjualan()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedDetailPenjualan = this.detailPenjualan[index]
            }
                
            this.showFormTambahUbah = true 
        },
        closeForm(reloadList) {
            this.formAction = null
            this.selectedDetailPenjualan = null
            this.showFormTambahUbah = false

            if(reloadList) {
                this.getDetailPenjualan()
            }
        },
    },
    computed: {
        nomorTransaksi: function () {
            if(this.penjualan != null) {
                return this.penjualan.jenis + "-" + moment(String(this.penjualan.tgl_transaksi)).format('DDMMYY') + "-" + this.penjualan.id
            }
        },
        statusTransaksi: function () {
            if(this.penjualan != null) {
                switch(this.penjualan.status) {
                    case 1:
                        return "Terbuka"
                        break
                    case 2:
                        return "Menunggu pembayaran"
                        break
                    case 3:
                        return "Selesai"
                        break
                }
            }
        },
        sparepartsClass: function () {
            if(this.penjualan != null) {
                return {
                    'col-sm-6': this.penjualan.jenis === 'SS',
                    'col-sm-12': this.penjualan.jenis === 'SP'
                }
            }
        },
        jasaserviceClass: function () {
            if(this.penjualan != null) {
                return {
                    'col-sm-6': this.penjualan.jenis === 'SS',
                    'col-sm-12': this.penjualan.jenis === 'SV'
                }
            }
        }
    },
    created() {
        this.getPenjualan()
        this.getDetailPenjualan()
    },
}
</script>