<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Detail Pengadaan Barang</h1>
            <div v-if="pengadaanBarang != null" class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                <button v-if="isOpen" @click="openForm('TAMBAH')" type="button" class="pull-right btn btn-success" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-plus"></i> Tambah</button>
                <button v-if="isWaitingForVerification" @click="openForm('VERIFIKASI')" type="button" class="pull-right btn btn-success" data-toggle="modal" data-target="#form-verifikasi"><i class="fa fa-check"></i> Verifikasi</button>
            </div>
        </section>
        
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="row" style="margin-bottom: 15px;">
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
                                    {{ statusTransaksi }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box">
                        <div class="box-body">
                            <table v-if="pengadaanBarang != null" id="mytable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Merk</th>
                                        <th>Jumlah Pesan</th>
                                        <th>Jumlah Datang</th>
                                        <th>Harga</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(detail_pengadaan_barang, index) in listDetailPengadaanBarang" v-bind:key="index">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ detail_pengadaan_barang.spareparts.kode }}</td>
                                        <td>{{ detail_pengadaan_barang.spareparts.nama }}</td>
                                        <td>{{ detail_pengadaan_barang.spareparts.merk }}</td>
                                        <td>{{ detail_pengadaan_barang.jumlah_pesan }}</td>
                                        <td>{{ detail_pengadaan_barang.jumlah_datang }}</td>
                                        <td>{{ detail_pengadaan_barang.harga | toCurrency }}</td>
                                        <td class="pull-right">
                                            <button v-if="isOpen" @click="openForm('UBAH', index)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button v-if="isOpen" @click="deleteDetailPengadaanBarang(detail_pengadaan_barang.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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