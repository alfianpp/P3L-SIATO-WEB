<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Kelola Data Cabang</h1>
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
                                        <th>Nama</th>
                                        <th>Nomor Telepon</th>
                                        <th>Alamat</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(cabang, index) in listCabang" v-bind:key="index">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ cabang.nama }}</td>
                                        <td>{{ cabang.nomor_telepon }}</td>
                                        <td>{{ cabang.alamat }}</td>
                                        <td class="pull-right">
                                            <button @click="openForm('UBAH', index)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button @click="deleteCabang(cabang.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
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
        v-bind:selected-cabang="selectedCabang" 
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
            listCabang: null,
            formAction: null,
            selectedCabang: null,
            showForm: false,
        }
    },
    methods: {
        getAllCabang() {
            axios.post(this.$root.app.url + 'api/data/cabang/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listCabang = response.data.data

                    if($.fn.dataTable.isDataTable( '#mytable')) {
                        $('#mytable').DataTable().destroy()
                    }
                }
            })
        },
        deleteCabang(kode) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus cabang ini?")) {
                axios.delete(this.$root.app.url + 'api/data/cabang/' + kode, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getAllCabang()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedCabang = this.listCabang[index]
            }
            this.showForm = true
        },
        closeForm(reloadList) {
            this.formAction = null
            this.selectedCabang = null
            this.showForm = false

            if(reloadList) {
                this.getAllCabang()
            }
        },
    },
    created() {
        this.getAllCabang()
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
                        {"orderable": false, "targets": [0, 2, 3, 4]},
                        {"searchable": false, "targets": [0, 2, 3, 4]}
                    ],
                })
            }
        })
    },
}
</script>