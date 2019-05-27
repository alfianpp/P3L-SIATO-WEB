<template>
    <div class="content-wrapper">
        <div id="hide" class="row">
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
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-sm-3 col-sm-offset-9" v-if="pengadaanBarang != null">
                            <b>No : {{ pengadaanBarang.supplier.id }}</b><br>
                            <b>Tanggal Transaksi : {{ pengadaanBarang.tgl_transaksi }}</b><br>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-4" style="border: 3px dashed #c3c3c3" v-if="pengadaanBarang != null">
                                    <b>Kepada Yth:</b><br>
                                    <b>{{ pengadaanBarang.supplier.nama }}</b><br>
                                    <b>{{ pengadaanBarang.supplier.alamat }}</b><br>
                                    <b>{{ pengadaanBarang.supplier.nomor_telepon_sales }}</b>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-9">
                                    <p>Mohon untuk disediakan barang-barang berikut :</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box">
                        <div class="box-body">
                            <table v-if="pengadaanBarang != null" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama Barang</th>
                                        <th>Merk</th>
                                        <th>Tipe Barang</th>
                                        <th>Satuan</th>
                                        <th>Jumlah</th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(detail_pengadaan_barang, index) in listDetailPengadaanBarang" v-bind:key="index">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ detail_pengadaan_barang.spareparts.nama }}</td>
                                        <td>{{ detail_pengadaan_barang.spareparts.merk }}</td>
                                        <td>{{ detail_pengadaan_barang.spareparts.tipe }}</td>
                                        <td>Box</td>
                                        <td>{{ detail_pengadaan_barang.jumlah_pesan }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="row" style="margin-bottom: 15px;">
                        <div class="col-md-2">
                            <p>Hormat kami, <br><br><br>
                             (Philips Purnomo)</p><br><br><br><br>
                        </div>
                    </div>
                     <br><br><br><br><br><br><a href="" @click.prevent="printme" target="_blank" class="btn btn-default"><i class="fa fa-print"></i>Print</a>
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
        getPengadaanBarangData() {
            axios.post(this.$root.app.url + 'api/transaksi/pengadaan/data/' + this.id, {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.pengadaanBarang = response.data.data
                }
            })
        },
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
            this.formAction = null
            this.selectedDetailPengadaanBarang = null
            this.showForm = false

            if(reloadList) {
                this.getDetailPengadaanBarang()
            }
        },
        printme(){
            window.print();
        }
    },
    computed: {
        statusTransaksi: function () {
            switch(this.pengadaanBarang.status) {
                case 1:
                    return "Terbuka"
                    break
                case 2:
                    return "Menunggu verifikasi"
                    break
                case 3:
                    return "Selesai"
                    break
            }
        },
        isOpen: function() {
            if(this.pengadaanBarang.status == 1) {
                return true
            }
            else {
                return false
            }
        },
        isWaitingForVerification: function () {
            if(this.pengadaanBarang.status == 2) {
                return true
            }
            else {
                return false
            }
        }
    },
    created() {
        this.getDetailPengadaanBarang()
        this.getPengadaanBarangData()
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
                        {"orderable": false, "targets": [0, 2, 3, 4, 5, 6, 7]},
                        {"searchable": false, "targets": [0, 2, 3, 4, 5, 6, 7]}
                    ],
                })
            }
        })
    },
}
</script>