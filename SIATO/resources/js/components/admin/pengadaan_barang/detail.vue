<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Detail Pengadaan Barang</h1>
        </section>
        
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <div class="row" style="margin-bottom: 15px;">
                                <div class="col-sm-6">
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <b>Supplier</b><br>
                                            <b>Tanggal Transaksi</b><br>
                                            <b>Status</b>
                                        </div>

                                        <div class="col-sm-8" v-if="PengadaanBarang != null">
                                            {{ PengadaanBarang.supplier.nama }} <br>
                                            {{ PengadaanBarang.tgl_transaksi }} <br>
                                            {{ getStatus(PengadaanBarang.status) }}
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <button @click="openForm('TAMBAH')" type="button" class="pull-right btn btn-success" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-plus"></i> Tambah</button>
                                </div>
                            </div>

                            <table id="mytable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Kode Spareparts</th>
                                        <th>Jumlah Pesan</th>
                                        <th>Jumlah Datang</th>
                                        <th>Harga</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(detail_pengadaan_barang, index) in listDetailPengadaanBarang" v-bind:key="index">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ detail_pengadaan_barang.kode_spareparts }}</td>
                                        <td>{{ formatNum(detail_pengadaan_barang.jumlah_pesan) }}</td>
                                        <td>{{ formatNum(detail_pengadaan_barang.jumlah_datang) }}</td>
                                        <td>Rp{{ formatCurrency(detail_pengadaan_barang.harga) }}</td>
                                        <td class="pull-right">
                                            <button @click="openForm('UBAH', index)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button @click="deleteDetailPengadaanBarang(detail_pengadaan_barang.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
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
        v-if="showForm == true" 
        v-bind:id-pengadaan-barang="id" 
        v-bind:form-action="formAction" 
        v-bind:selected-detail-pengadaan-barang="selectedDetailPengadaanBarang" 
        v-on:close="closeForm">
        </form-tambah-ubah>
    </div>
</template>

<script>
import formTambahUbah from './form-tambah-ubah-detail.vue';
import numeral from 'numeral'

export default {
    components: {
      formTambahUbah
    },
    props: ['id'],
    data: function() {
        return {
            PengadaanBarang: null,
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
                    this.PengadaanBarang = response.data.data
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
        getStatus(id) {
            switch(id) {
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
        formatNum(val) {
            return numeral(val).format('0,0')
        },
        formatCurrency(val) {
            return numeral(val).format('0,0.00')
        },
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
                        {"orderable": false, "targets": [0, 2, 3, 4, 5]},
                        {"searchable": false, "targets": [0, 2, 3, 4, 5]}
                    ],
                })
            }
        })
    },
}
</script>