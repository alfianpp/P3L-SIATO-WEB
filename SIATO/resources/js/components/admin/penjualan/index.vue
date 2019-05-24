<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Penjualan</h1>
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
                                        <th>No. Transaksi</th>
                                        <th>Konsumen</th>
                                        <th>Telepon</th>
                                        <th>Tanggal Transaksi</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(penjualan, index) in listPenjualan" v-bind:key="index">
                                        <td>{{ penjualan.jenis + '|' + penjualan.tgl_transaksi + '|' + penjualan.id | nomorTransaksiPenjualan }}</td>
                                        <td>{{ penjualan.konsumen.nama }}</td>
                                        <td>{{ penjualan.konsumen.nomor_telepon }}</td>
                                        <td>{{ penjualan.tgl_transaksi }}</td>
                                        <td>{{ penjualan.status | statusPenjualan }}</td>
                                        <td class="pull-right">
                                            <a :href="'/admin/transaksi/penjualan/detail/' + penjualan.id" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i> Detail</a>
                                            <button v-if="penjualan.status == 1" @click="openForm('UBAH', index)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button v-if="penjualan.status == 1" @click="deletePenjualan(penjualan.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
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
        v-bind:selected-penjualan="selectedPenjualan" 
        v-on:close="closeForm">
        </form-tambah-ubah>
    </div>
</template>

<script>
import formTambahUbah from './form-tambah-ubah.vue';

export default {
    components: {
      formTambahUbah
    },
    data: function() {
        return {
            listPenjualan: null,
            formAction: null,
            selectedPenjualan: null,
            showForm: false,
        }
    },
    methods: {
        getAllPenjualan() {
            axios.post(this.$root.app.url + 'api/transaksi/penjualan/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listPenjualan = response.data.data

                    if($.fn.dataTable.isDataTable( '#mytable')) {
                        $('#mytable').DataTable().destroy()
                    }
                }
            })
        },
        deletePenjualan(id) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus penjualan ini?")) {
                axios.delete(this.$root.app.url + 'api/transaksi/penjualan/data/' + id, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getAllPenjualan()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedPenjualan = this.listPenjualan[index]
            }
            this.showForm = true 
        },
        closeForm(reloadList) {
            this.formAction = null
            this.selectedPenjualan = null
            this.showForm = false

            if(reloadList) {
                this.getAllPenjualan()
            }
        },
    },
    created() {
        this.getAllPenjualan()
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
                    'order': [],
                    'columnDefs': [
                        {"orderable": false, "targets": [0, 2, 5]},
                        {"searchable": false, "targets": [5]}
                    ],
                })
            }
        })
    },
}
</script>