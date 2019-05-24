<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Detail Penjualan {{ (penjualan != null) ? $options.filters.nomorTransaksiPenjualan(penjualan.jenis + '|' + penjualan.tgl_transaksi + '|' + penjualan.id) : '' }}</h1>
            <div v-if="penjualan != null" class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                <button v-if="isOpen" @click="verifikasiPenjualan()" type="button" class="btn btn-warning"><i class="fa fa-check"></i> Verifikasi</button>
                <button v-if="isOpen && (penjualan.jenis == 'SV' || penjualan.jenis == 'SS')" @click="openForm('TAMBAH')" type="button" class="btn btn-success" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        </section>
        
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div v-if="penjualan != null" class="row no-print" style="margin-bottom: 15px;">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <b>Konsumen</b><br>
                                    <b>Telepon</b>
                                </div>

                                <div class="col-sm-8">
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

                                <div class="col-sm-8">
                                    {{ penjualan.tgl_transaksi }}<br>
                                    {{ penjualan.status | statusPenjualan }}
                                </div>
                            </div>
                        </div>
                    </div>
                
                    <div v-for="(detail, index) in detailPenjualan" v-bind:key="index" class="box no-print">
                        <div class="box-body">
                            <div v-if="penjualan != null && (penjualan.jenis == 'SV' || penjualan.jenis == 'SS')" class="row" style="margin-bottom: 15px;">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <b>Motor</b><br>
                                            <b>Montir</b>
                                        </div>

                                        <div class="col-sm-8">
                                            {{ detail.kendaraan.merk }} {{ detail.kendaraan.tipe }} {{ detail.kendaraan.nomor_polisi }}<br>
                                            {{ detail.montir.nama }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="pull-right">
                                        <button v-if="isOpen" @click="doPrint(index)" type="button" class="btn btn-sm btn-default"><i class="fa fa-print"></i> Print SPK</button>
                                        <button v-if="isOpen" @click="openForm('UBAH', index)" type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-pencil"></i> Ubah</button>
                                        <button v-if="isOpen" @click="deleteDetailPenjualan(detail.id)" type="button" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Hapus</button>
                                    </div>
                                    
                                </div> 
                            </div>

                            <div v-if="penjualan != null" class="row">
                                <div v-if="penjualan.jenis == 'SP' || penjualan.jenis == 'SS'" :class="sparepartsClass">
                                    <detail-spareparts 
                                    v-bind:index="index+1"
                                    v-bind:id-detail-penjualan="detail.id"
                                    v-bind:is-open="isOpen"
                                    :ref="'spareparts-' + index">
                                    </detail-spareparts>
                                </div>

                                <div v-if="penjualan.jenis == 'SV' || penjualan.jenis == 'SS'" :class="jasaserviceClass">
                                    <detail-jasa-service
                                    v-bind:index="index+1"
                                    v-bind:id-detail-penjualan="detail.id"
                                    v-bind:is-open="isOpen"
                                    :ref="'jasaservice-' + index">
                                    </detail-jasa-service>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div v-if="print.index != null" id="hide-in-view"  class="box">
                        <div class="row">
                            <div class="col-xs-10 col-xs-offset-1">
                                <div class="row">
                                    <div class="col-xs-4"><img :src="$root.app.url + 'images/logo.png'" class="img-responsive" alt="logo"></div>
                                    <div class="col-xs-8 text-center">
                                        <h1><b>A</b>TM<b>A</b> <b>A</b>UTO</h1>
                                        <h5>MOTORCYCLE SPAREPARTS AND SERVICES</h5>
                                        <p>
                                            Jl. Babarsari N o. 43 Yogyakarta 552181
                                            Telp. (0274) 487711
                                            http://www.atmaauto.com
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h4 class="text-center"><b>SURAT PERINTAH KERJA</b></h4>

                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h3>{{ penjualan.jenis + '|' + penjualan.tgl_transaksi + '|' + penjualan.id | nomorTransaksiPenjualan }}</h3>
                                </div>

                                <div class="col-xs-6">
                                    <small class="pull-right">{{ penjualan.tgl_transaksi }}</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            Cust<br>
                                            Telepon<br>
                                            Motor
                                        </div>

                                        <div class="col-xs-9">
                                            {{ penjualan.konsumen.nama }}<br>
                                            {{ penjualan.konsumen.nomor_telepon }}<br>
                                            {{ detailPenjualan[print.index].kendaraan.merk }} {{ detailPenjualan[print.index].kendaraan.tipe }} {{ detailPenjualan[print.index].kendaraan.nomor_polisi }}
                                        </div>
                                    </div>                                 
                                </div>

                                <div class="col-xs-6">
                                    <div class="row">
                                        <div class="col-xs-3 col-xs-offset-3">
                                            CS<br>
                                            Montir
                                        </div>

                                        <div class="col-xs-6">
                                            {{ penjualan.cs.nama }}<br>
                                            {{ detailPenjualan[print.index].montir.nama }}
                                        </div>
                                    </div>   
                                </div>
                            </div>

                            <hr style="margin-bottom: 10px;">
                            <h4 class="text-center" style="margin-top: 0px; margin-bottom: 0px;"><b>SPAREPARTS</b></h4>
                            <hr style="margin-top: 10px;">

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Merk</th>
                                        <th>Rak</th>
                                        <th class="text-right">Jumlah</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(detail, index) in print.listPenjualanSpareparts" v-bind:key="index">
                                        <td>{{detail.spareparts.kode}}</td>
                                        <td>{{detail.spareparts.nama}}</td>
                                        <td>{{detail.spareparts.merk}}</td>
                                        <td>{{detail.spareparts.kode_peletakan}}</td>
                                        <td class="text-right">{{detail.jumlah}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <hr style="margin-bottom: 10px;">
                            <h4 class="text-center" style="margin-top: 0px; margin-bottom: 0px;"><b>JASA SERVICE</b></h4>
                            <hr style="margin-top: 10px;">

                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nama</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th class="text-right">Jumlah</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(detail, index) in print.listPenjualanJasaService" v-bind:key="index">
                                        <td>{{detail.jasa_service.nama}}</td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right">{{detail.jumlah}}</td>
                                    </tr>
                                </tbody>
                            </table>
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
            print: {
                index: null,
                listPenjualanSpareparts: null,
                listPenjualanJasaService: null
            },
        }
    },
    methods: {
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
        verifikasiPenjualan() {
            if(this.detailPenjualan.length > 0) {
                if(confirm("Penjualan tidak dapat diubah lagi setelah Anda memilih untuk menyelesaikannya. Apakah Anda ingin melanjutkan untuk menyelesaikan penjualan ini?")) {
                    axios.put(this.$root.app.url + 'api/transaksi/penjualan/data/' + this.idPenjualan, {
                        status: 2,
                        api_key: this.$root.api_key,
                    })
                    .then(response => {
                        if(response.data.error == false) {
                            alert(response.data.message)
                            window.location.replace(this.$root.app.url + 'admin/transaksi/penjualan');
                        }
                    })
                }
            }
            else {
                alert("Penjualan belum bisa diverifikasi.")
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
        doPrint(index) {
            this.print.index = index
            this.print.listPenjualanSpareparts = this.$refs['spareparts-' + index][0].listPenjualanSpareparts
            this.print.listPenjualanJasaService = this.$refs['jasaservice-' + index][0].listPenjualanJasaService

            this.$nextTick(() => {
                window.print();
                
                this.print.index = null
                this.print.listPenjualanSpareparts = null
                this.print.listPenjualanJasaService = null
            });
        },
    },
    computed: {
        sparepartsClass: function () {
            return {
                'col-sm-6': this.penjualan.jenis === 'SS',
                'col-sm-12': this.penjualan.jenis === 'SP'
            }
        },
        jasaserviceClass: function () {
            return {
                'col-sm-6': this.penjualan.jenis === 'SS',
                'col-sm-12': this.penjualan.jenis === 'SV'
            }
        },
        isOpen: function() {
            return (this.penjualan.status == 1)
        }
    },
    created() {
        this.getDetailPenjualan()
        this.getPenjualan()
    },
}
</script>

<style scoped>
#hide-in-view {
    display: none;
}

@media print {
    .box {
        border-top: none;
    }

    #hide-in-view {
        display: inline;
    }
}
</style>