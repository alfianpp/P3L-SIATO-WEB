<template>
    <div>
        <div class="row">
            <div class="col-sm-6">
                <h4>Spareparts</h4>
            </div>

            <div class="col-sm-6">
                <div class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 4px; right: 15px;">
                    <button v-if="isOpen" @click="openForm('TAMBAH')" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-plus"></i> Tambah</button>
                </div>
            </div>
        </div>

        <table :id="'tabel-detail-penjualan-spareparts-' + index" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Spareparts</th>
                    <th>Merk</th>
                    <th>Jumlah</th>
                    <th>Harga</th>
                    <th></th>
                </tr>
            </thead>
            
            <tbody>
                <tr v-for="(penjualanSpareparts, index) in listPenjualanSpareparts" v-bind:key="index">
                    <td>{{ index+1 }}</td>
                    <td>{{ penjualanSpareparts.spareparts.nama }}</td>
                    <td>{{ penjualanSpareparts.spareparts.merk }}</td>
                    <td>{{ penjualanSpareparts.jumlah }}</td>
                    <td>{{ penjualanSpareparts.harga | toCurrency }}</td>
                    <td class="pull-right">
                        <button v-if="isOpen" @click="openForm('UBAH', index)" type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-pencil"></i></button>
                        <button v-if="isOpen" @click="deletePenjualanSpareparts(penjualanSpareparts.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>

        <form-tambah-ubah-penjualan-spareparts 
        v-if="showFormTambahUbah == true" 
        v-bind:id-detail-penjualan="idDetailPenjualan" 
        v-bind:form-action="formAction" 
        v-bind:selected-penjualan-spareparts="selectedPenjualanSpareparts" 
        v-on:close="closeForm">
        </form-tambah-ubah-penjualan-spareparts>
    </div>               
</template>

<script>
import formTambahUbahPenjualanSpareparts from './form-tambah-ubah-detail-spareparts.vue';

export default {
    components: {
      formTambahUbahPenjualanSpareparts
    },
    props: ['index', 'idDetailPenjualan', 'isOpen'],
    data: function() {
        return {
            listPenjualanSpareparts: null,
            formAction: null,
            selectedPenjualanSpareparts: null,
            showFormTambahUbah: false,
        }
    },
    methods: {
        getPenjualanSpareparts() {
            axios.post(this.$root.app.url + 'api/transaksi/penjualan/detail/spareparts/' + this.idDetailPenjualan, {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listPenjualanSpareparts = response.data.data

                    if($.fn.dataTable.isDataTable('#tabel-detail-penjualan-spareparts-' + this.index)) {
                        $('#tabel-detail-penjualan-spareparts-' + this.index).DataTable().destroy()
                    }
                }
            })
        },
        deletePenjualanSpareparts(id) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus spareparts ini?")) {
                axios.delete(this.$root.app.url + 'api/transaksi/penjualan/detail/spareparts/data/' + id, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getPenjualanSpareparts()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedPenjualanSpareparts = this.listPenjualanSpareparts[index]
            }
            this.showFormTambahUbah = true 
        },
        closeForm(reloadList) {
            this.formAction = null
            this.selectedPenjualanSpareparts = null
            this.showFormTambahUbah = false

            if(reloadList) {
                this.getPenjualanSpareparts()
            }
        },
    },
    created() {
        this.getPenjualanSpareparts()
    },
    updated() {
        this.$nextTick(function () {
            if(!$.fn.dataTable.isDataTable('#tabel-detail-penjualan-spareparts-' + this.index)) {
                $('#tabel-detail-penjualan-spareparts-' + this.index).DataTable({
                    'autoWidth'   : true,
                    'info'        : false,
                    'lengthChange': true,
                    'ordering'    : false,
                    'paging'      : true,
                    'searching'   : false,
                })
            }
        })
    },
}
</script>