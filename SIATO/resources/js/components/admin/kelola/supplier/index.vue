<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Kelola Data Supplier</h1>
            <div class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                <button @click="openForm('TAMBAH')" id="btnTambahSupplier" type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#form-tambah-ubah-supplier"><i class="fa fa-plus"></i> Tambah</button>
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
                                        <th>Nama</th>
                                        <th>Alamat</th>
                                        <th>Nama Sales</th>
                                        <th>Nomor Telepon Sales</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(supplier, index) in listSupplier" v-bind:key="index">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ supplier.nama }}</td>
                                        <td>{{ supplier.alamat }}</td>
                                        <td>{{ supplier.nama_sales }}</td>
                                        <td>{{ supplier.nomor_telepon_sales }}</td>
                                        <td class="pull-right">
                                            <button @click="openForm('UBAH', index)" :id="'btnUbah' + index" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah-supplier"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button @click="deleteSupplier(supplier.id)" :id="'btnHapus' + index" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
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
        v-bind:selected-supplier="selectedSupplier" 
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
            listSupplier: null,
            formAction: null,
            selectedSupplier: null,
            showForm: false,
        }
    },
    methods: {
        getAllSupplier() {
            axios.post(this.$root.app.url + 'api/data/supplier/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listSupplier = response.data.data

                    if($.fn.dataTable.isDataTable( '#mytable')) {
                        $('#mytable').DataTable().destroy()
                    }
                }
            })
        },
        deleteSupplier(id) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus supplier ini?")) {
                axios.delete(this.$root.app.url + 'api/data/supplier/' + id, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getAllSupplier()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedSupplier = this.listSupplier[index]
            }
            this.showForm = true
        },
        closeForm(reloadList) {
            this.formAction = null
            this.selectedSupplier = null
            this.showForm = false

            if(reloadList) {
                this.getAllSupplier()
            }
        },
    },
    created() {
        this.getAllSupplier()
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
                        {"orderable": false, "targets": [0, 2, 4, 5]},
                        {"searchable": false, "targets": [0, 5]}
                    ],
                })
            }
        })
    },
}
</script>