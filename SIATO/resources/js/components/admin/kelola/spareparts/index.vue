<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Kelola Data Spareparts</h1>
            <div class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                <button @click="openForm('TAMBAH')" type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#form-tambah-ubah-spareparts"><i class="fa fa-plus"></i> Tambah</button>
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
                                        <th>Kode</th>
                                        <th>Nama</th>
                                        <th>Merk</th>
                                        <th>Tipe</th>
                                        <th>Harga Beli</th>
                                        <th>Harga Jual</th>
                                        <th>Peletakan</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(spareparts, index) in listSpareparts" v-bind:key="index">
                                        <td>{{ spareparts.kode }}</td>
                                        <td>{{ spareparts.nama }}</td>
                                        <td>{{ spareparts.merk }}</td>
                                        <td>{{ spareparts.tipe }}</td>
                                        <td>{{ spareparts.harga_beli | toCurrency }}</td>
                                        <td>{{ spareparts.harga_jual | toCurrency }}</td>
                                        <td>{{ spareparts.kode_peletakan }}</td>
                                        <td class="pull-right">
                                            <button @click="openForm('UBAH', index)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah-spareparts"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button @click="deleteSpareparts(spareparts.kode)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
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
        v-bind:selected-spareparts="selectedSpareparts" 
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
            listSpareparts: null,
            formAction: null,
            selectedSpareparts: null,
            showForm: false,
        }
    },
    methods: {
        getAllSpareparts() {
            axios.post(this.$root.app.url + 'api/data/spareparts/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listSpareparts = response.data.data

                    if($.fn.dataTable.isDataTable( '#mytable')) {
                        $('#mytable').DataTable().destroy()
                    }
                }
            })
        },
        deleteSpareparts(kode) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus spareparts ini?")) {
                axios.delete(this.$root.app.url + 'api/data/spareparts/' + kode, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getAllSpareparts()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedSpareparts = this.listSpareparts[index]
            }
            this.showForm = true
        },
        closeForm(reloadList) {
            this.formAction = null
            this.selectedSpareparts = null
            this.showForm = false

            if(reloadList) {
                this.getAllSpareparts()
            }
        },
    },
    created() {
        this.getAllSpareparts()
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
                        {"type": "num-fmt", "targets": [4, 5]},
                        {"orderable": false, "targets": [0, 6, 7]},
                        {"searchable": false, "targets": [4, 5, 7]}
                    ],
                })
            }
        })
    },
}
</script>