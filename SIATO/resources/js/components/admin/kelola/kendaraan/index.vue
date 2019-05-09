<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Kelola Data Kendaraan</h1>
            <div class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                <button @click="openForm('TAMBAH')" type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#form-tambah-ubah-kendaraan"><i class="fa fa-plus"></i> Tambah</button>
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
                                        <th>Nomor Polisi</th>
                                        <th>Merk</th>
                                        <th>Tipe</th>
                                        <th>Pemilik</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(kendaraan, index) in listKendaraan" v-bind:key="index">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ kendaraan.nomor_polisi }}</td>
                                        <td>{{ kendaraan.merk }}</td>
                                        <td>{{ kendaraan.tipe }}</td>
                                        <td>{{ kendaraan.pemilik.nama }}</td>
                                        <td class="pull-right">
                                            <button @click="openForm('UBAH', index)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah-kendaraan"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button @click="deleteKendaraan(kendaraan.nomor_polisi)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
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
        v-bind:selected-kendaraan="selectedKendaraan" 
        v-on:close="closeForm">
        </form-tambah-ubah>
    </div>
</template>

<script>
import formTambahUbah from './form-tambah-ubah.vue';

export default {
    components: {
      formTambahUbah,
    },
    data: function() {
        return {
            listKendaraan: null,
            formAction: null,
            selectedKendaraan: null,
            showForm: false,
        }
    },
    methods: {
        getAllKendaraan() {
            axios.post(this.$root.app.url + 'api/data/kendaraan/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listKendaraan = response.data.data

                    if($.fn.dataTable.isDataTable( '#mytable')) {
                        $('#mytable').DataTable().destroy()
                    }
                }
            })
        },
        deleteKendaraan(kode) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus kendaraan ini?")) {
                axios.delete(this.$root.app.url + 'api/data/kendaraan/' + kode, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getAllKendaraan()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedKendaraan = this.listKendaraan[index]
            }
            this.showForm = true
        },
        closeForm(reloadList) {
            this.formAction = null
            this.selectedKendaraan = null
            this.showForm = false

            if(reloadList) {
                this.getAllKendaraan()
            }
        },
    },
    created() {
        this.getAllKendaraan()
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
                        {"orderable": false, "targets": [0, 1, 5]},
                        {"searchable": false, "targets": [0, 5]}
                    ],
                })
            }
        })
    },
}
</script>