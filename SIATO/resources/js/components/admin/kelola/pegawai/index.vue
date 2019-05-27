<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Kelola Data Pegawai</h1>
            <div class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                <button @click="openForm('TAMBAH')" type="button" class="btn btn-block btn-success" data-toggle="modal" data-target="#form-tambah-ubah-pegawai"><i class="fa fa-plus"></i> Tambah</button>
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
                                        <th>Nomor telepon</th>
                                        <th>Alamat</th>
                                        <th>Gaji</th>
                                        <th>Peran</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(pegawai, index) in listPegawai" v-bind:key="index">
                                        <td>{{ index+1 }}</td>
                                        <td>{{ pegawai.nama }}</td>
                                        <td>{{ pegawai.nomor_telepon }}</td>
                                        <td>{{ pegawai.alamat }}</td>
                                        <td>{{ pegawai.gaji | toCurrency }}</td>
                                        <td>{{ pegawai.role | peranPegawai }}</td>
                                        <td class="pull-right">
                                            <button @click="openForm('UBAH', index)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah-pegawai"><i class="fa fa-pencil"></i> Ubah</button>
                                            <button @click="deletePegawai(pegawai.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i> Hapus</button>
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
        v-bind:selected-pegawai="selectedPegawai" 
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
            listPegawai: null,
            formAction: null,
            selectedPegawai: null,
            showForm: false,
        }
    },
    methods: {
        getAllPegawai() {
            axios.post(this.$root.app.url + 'api/data/pegawai/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listPegawai = response.data.data

                    if($.fn.dataTable.isDataTable( '#mytable')) {
                        $('#mytable').DataTable().destroy()
                    }
                }
            })
        },
        deletePegawai(id) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus pegawai ini?")) {
                axios.delete(this.$root.app.url + 'api/data/pegawai/' + id, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getAllPegawai()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedPegawai = this.listPegawai[index]
            }
            this.showForm = true
        },
        closeForm(reloadList) {
            this.formAction = null
            this.selectedPegawai = null
            this.showForm = false

            if(reloadList) {
                this.getAllPegawai()
            }
        },
    },
    filters: {
        peranPegawai: function (value) {
            switch(value) {
                case 1:
                    return "CS"
                    break
                case 2:
                    return "Kasir"
                    break
                case 3:
                    return "Montir"
                    break
            }
        }
    },
    created() {
        this.getAllPegawai()
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
                        {"orderable": false, "targets": [0, 2, 3, 6]},
                        {"searchable": false, "targets": [0, 4, 5, 6]}
                    ],
                })
            }
        })
    },
}
</script>