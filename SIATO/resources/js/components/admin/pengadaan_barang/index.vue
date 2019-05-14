<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Pengadaan Barang</h1>
            <div class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                <button @click="openForm('TAMBAH')" type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-plus"></i> Tambah</button>
            </div>
        </section>
        
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-body">
                            <table id="mytable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Supplier</th>
                                        <th>Total</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(pengadaan_barang, index) in listPengadaanBarang" v-bind:key="index">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ pengadaan_barang.supplier.nama }}</td>
                                        <td>{{ pengadaan_barang.total | toCurrency }}</td>
                                        <td>{{ pengadaan_barang.tgl_transaksi }}</td>
                                        <td>{{ pengadaan_barang.status | statusTransaksi }}</td>
                                        <td class="pull-right">
                                            <button v-if="pengadaan_barang.status != 3" @click="print(index)" type="button" class="btn btn-default btn-sm"><i class="fa fa-print"></i> Print</button>
                                            <a :href="'/admin/transaksi/pengadaan_barang/detail/' + pengadaan_barang.id" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i> Detail</a>
                                            <button v-if="pengadaan_barang.status == 1" @click="openForm('UBAH', index)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button v-if="pengadaan_barang.status != 3" @click="deletePengadaanBarang(pengadaan_barang.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
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
        v-bind:form-action="formAction" 
        v-bind:selected-pengadaan-barang="selectedPengadaanBarang" 
        v-on:close="closeForm">
        </form-tambah-ubah>
    </div>
</template>

<script>
import formTambahUbah from './form-tambah-ubah.vue';
import numeral from 'numeral'

export default {
    components: {
      formTambahUbah
    },
    data: function() {
        return {
            listPengadaanBarang: null,
            formAction: null,
            selectedPengadaanBarang: null,
            showForm: false,
        }
    },
    methods: {
        getAllPengadaanBarang() {
            axios.post(this.$root.app.url + 'api/transaksi/pengadaan/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listPengadaanBarang = response.data.data

                    if($.fn.dataTable.isDataTable( '#mytable')) {
                        $('#mytable').DataTable().destroy()
                    }
                }
            })
        },
        deletePengadaanBarang(id) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus pengadaan barang ini?")) {
                axios.delete(this.$root.app.url + 'api/transaksi/pengadaan/data/' + id, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getAllPengadaanBarang()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedPengadaanBarang = this.listPengadaanBarang[index]
            }
            this.showForm = true 
        },
        closeForm(reloadList) {
            this.formAction = null
            this.selectedPengadaanBarang = null
            this.showForm = false

            if(reloadList) {
                this.getAllPengadaanBarang()
            }
        },
        print(index) {
            alert("Belum bisa dipakai.")
        },
    },
    filters: {
        statusTransaksi: function (value) {
            switch(value) {
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
    },
    created() {
        this.getAllPengadaanBarang()
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
                        {"orderable": false, "targets": [0, 3, 5]},
                        {"searchable": false, "targets": [0, 2, 5]}
                    ],
                })
            }
        })
    },
}
</script>