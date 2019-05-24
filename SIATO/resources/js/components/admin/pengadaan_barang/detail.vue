<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Detail Pengadaan Barang</h1>
            <div v-if="pengadaanBarang != null" class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                <button v-if="isOpen || isWaitingForVerification" @click="print()" type="button" class="btn btn-default" style="margin-right:5px;"><i class="fa fa-print"></i> Print</button>
                <button v-if="isOpen" @click="openForm('TAMBAH')" type="button" class="pull-right btn btn-success" data-toggle="modal" data-target="#form-tambah-ubah-detail-pengadaan-barang"><i class="fa fa-plus"></i> Tambah</button>
                <button v-if="isWaitingForVerification" @click="openForm('VERIFIKASI')" type="button" class="pull-right btn btn-success" data-toggle="modal" data-target="#form-verifikasi-pengadaan-barang"><i class="fa fa-check"></i> Verifikasi</button>
            </div>
        </section>
        
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row no-print" style="margin-bottom: 15px;">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-4">
                                    <b>Supplier</b><br>
                                    <b>Tanggal Transaksi</b><br>
                                    <b>Status</b>
                                </div>

                                <div class="col-sm-8" v-if="pengadaanBarang != null">
                                    {{ pengadaanBarang.supplier.nama }} <br>
                                    {{ pengadaanBarang.tgl_transaksi }} <br>
                                    {{ pengadaanBarang.status | statusPengadaanBarang }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box no-print">
                        <div class="box-body">
                            <table v-if="pengadaanBarang != null" id="mytable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Merk</th>
                                        <th>Tipe</th>
                                        <th>Jumlah Pesan</th>
                                        <th>Jumlah Datang</th>
                                        <th>Harga Satuan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(detail, index) in listDetailPengadaanBarang" v-bind:key="index">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ detail.spareparts.kode }}</td>
                                        <td>{{ detail.spareparts.nama }}</td>
                                        <td>{{ detail.spareparts.merk }}</td>
                                        <td>{{ detail.spareparts.tipe }}</td>
                                        <td>{{ detail.jumlah_pesan }}</td>
                                        <td>{{ (detail.jumlah_datang != null) ? detail.jumlah_datang : '-' }}</td>
                                        <td>{{ (detail.harga != null) ? $options.filters.toCurrency(detail.harga) : '-' }}</td>
                                        <td class="pull-right">
                                            <button v-if="isOpen" @click="openForm('UBAH', index)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah-detail-pengadaan-barang"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button v-if="isOpen" @click="deleteDetailPengadaanBarang(detail.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div v-if="pengadaanBarang != null" id="hide-in-view" class="box">
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

                        <h4 class="text-center"><b>SURAT PEMESANAN</b></h4>

                        <div class="box-body">
                            <div class="row" style="margin-bottom:20px;">
                                <div class="col-xs-4 col-xs-offset-8">
                                    No: {{pengadaanBarang.id}}<br>
                                    Tanggal: {{dateNow}}
                                </div>
                            </div>

                            <div class="row" style="margin-bottom:20px;">
                                <div class="col-xs-5">
                                    <div style="border: 2px #6E6E6E dashed; padding: 7px;">
                                        Kepada Yth:<br>
                                        {{pengadaanBarang.supplier.nama}}<br>
                                        {{pengadaanBarang.supplier.alamat}}
                                    </div>
                                </div>
                            </div>

                            <p>Mohon untuk disediakan barang-barang berikut :</p>
                            <table v-if="pengadaanBarang != null" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Barang</th>
                                        <th>Merk</th>
                                        <th>Tipe Barang</th>
                                        <th>Satuan</th>
                                        <th class="text-right">Jumlah</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(detail, index) in listDetailPengadaanBarang" v-bind:key="index">
                                        <td>{{index+1}}</td>
                                        <td>{{detail.spareparts.nama}}</td>
                                        <td>{{detail.spareparts.merk}}</td>
                                        <td>{{detail.spareparts.tipe}}</td>
                                        <td></td>
                                        <td class="text-right">{{detail.jumlah_pesan}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row" style="margin-top:20px;">
                                <div class="col-xs-3 col-xs-offset-9">
                                    Hormat Kami,<br><br><br><br><br>
                                    ()
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <form-tambah-ubah 
        v-if="showForm == true && formAction == 'TAMBAH' || formAction == 'UBAH'" 
        v-bind:id-pengadaan-barang="id" 
        v-bind:form-action="formAction" 
        v-bind:selected-detail-pengadaan-barang="selectedDetailPengadaanBarang" 
        v-on:close="closeForm">
        </form-tambah-ubah>

        <form-verifikasi
        v-if="showForm == true && formAction == 'VERIFIKASI'"
        v-bind:id-pengadaan-barang="id"
        v-on:close="closeForm">
        </form-verifikasi>
    </div>
</template>

<script>
import formTambahUbah from './form-tambah-ubah-detail.vue';
import formVerifikasi from './form-verifikasi.vue';
import moment from 'moment'
import 'moment/locale/id'
moment.locale('id')

export default {
    components: {
      formTambahUbah, formVerifikasi
    },
    props: ['id'],
    data: function() {
        return {
            pengadaanBarang: null,
            listDetailPengadaanBarang: null,
            formAction: null,
            selectedDetailPengadaanBarang: null,
            showForm: false,
        }
    },
    methods: {
        getDetailPengadaanBarang() {
            axios.post(this.$root.app.url + 'api/transaksi/pengadaan/detail/' + this.id, {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listDetailPengadaanBarang = response.data.data

                    if($.fn.dataTable.isDataTable( '#mytable')) {
                        $('#mytable').DataTable().destroy()
                    }
                }
            })
        },
        getPengadaanBarang() {
            axios.post(this.$root.app.url + 'api/transaksi/pengadaan/data/' + this.id, {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.pengadaanBarang = response.data.data
                }
            })
        },
        deleteDetailPengadaanBarang(id) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus pengadaan barang ini?")) {
                axios.delete(this.$root.app.url + 'api/transaksi/pengadaan/detail/' + id, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getDetailPengadaanBarang()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedDetailPengadaanBarang = this.listDetailPengadaanBarang[index]
            }
            this.showForm = true 
        },
        closeForm(reloadList) {
            var _temp = this.formAction
            this.formAction = null
            this.selectedDetailPengadaanBarang = null
            this.showForm = false

            if(reloadList) {
                this.getDetailPengadaanBarang()
            }

            if(_temp == 'VERIFIKASI') {
                this.getPengadaanBarang()
            }
        },
        print() {
            if(this.listDetailPengadaanBarang.length > 0) {
                if(this.pengadaanBarang.status == 1) {
                    if(confirm("Pesanan tidak dapat diubah lagi setelah Anda memilih untuk mencetak Surat Pemesanan. Apakah Anda ingin melanjutkan untuk mencetak Surat Pemesanan?")) {
                        axios.put(this.$root.app.url + 'api/transaksi/pengadaan/data/' + this.id, {
                            status: 2,
                            api_key: this.$root.api_key,
                        })
                        .then(response => {
                            this.response = response.data
                            if(this.response.error == false) {
                                alert(this.response.message)
                                this.getPengadaanBarang()
                            }
                        })
                    }
                }

                window.print()
            }
            else {
                alert("Belum ada data untuk dicetak.")
            }
        }
    },
    computed: {
        isOpen: function() {
            return (this.pengadaanBarang.status == 1)
        },
        isWaitingForVerification: function () {
            return (this.pengadaanBarang.status == 2)
        },
        dateNow: function () {
            return moment().format('DD MMMM YYYY')
        },
    },
    created() {
        this.getDetailPengadaanBarang()
        this.getPengadaanBarang()
    },
    updated() {
        this.$nextTick(function () {
            if(!$.fn.dataTable.isDataTable('#mytable')) {
                $('#mytable').DataTable({
                    'autoWidth'   : true,
                    'info'        : true,
                    'lengthChange': true,
                    'ordering'    : true,
                    'paging'      : true,
                    'searching'   : true,
                    'order': [[0, 'asc']],
                    'columnDefs': [
                        {"type": "num-fmt", "targets": [7]},
                        {"orderable": false, "targets": [0, 1, 8]},
                        {"searchable": false, "targets": [0, 5, 6, 7, 8]}
                    ],
                })
            }
        })
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