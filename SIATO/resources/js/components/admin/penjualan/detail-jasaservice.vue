<template>
    <div>
        <div class="row">
            <div class="col-sm-6">
                <h4>Jasa Service</h4>
            </div>

            <div class="col-sm-6">
                <div class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 4px; right: 15px;">
                    <button @click="openForm('TAMBAH')" type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#form-tambah-ubah"><i class="fa fa-plus"></i> Tambah</button>
                </div>
            </div>
        </div>

        <table :id="'tabel-detail-penjualan-jasa-service-' + index" class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th>No.</th>
                    <th>Jasa Service</th>
                    <th>Harga</th>
                    <th></th>
                </tr>
            </thead>
            
            <tbody>
                <tr v-for="(penjualanJasaService, index) in listPenjualanJasaService" v-bind:key="index">
                    <td>{{ index+1 }}</td>
                    <td>{{ penjualanJasaService.jasa_service.nama }}</td>
                    <td>{{ penjualanJasaService.harga | toCurrency }}</td>
                    <td class="pull-right">
                        <button @click="deletePenjualanJasaService(penjualanJasaService.id)" type="button" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>

        <form-tambah-ubah-penjualan-jasa-service 
        v-if="showFormTambahUbah == true" 
        v-bind:id-detail-penjualan="idDetailPenjualan" 
        v-bind:form-action="formAction" 
        v-bind:selected-penjualan-jasa-service="selectedPenjualanJasaService" 
        v-on:close="closeForm">
        </form-tambah-ubah-penjualan-jasa-service>
    </div>               
</template>

<script>
import formTambahUbahPenjualanJasaService from './form-tambah-ubah-detail-jasaservice.vue';

export default {
    components: {
      formTambahUbahPenjualanJasaService
    },
    props: ['index', 'idDetailPenjualan'],
    data: function() {
        return {
            listPenjualanJasaService: null,
            formAction: null,
            selectedPenjualanJasaService: null,
            showFormTambahUbah: false,
        }
    },
    methods: {
        getPenjualanJasaService() {
            axios.post(this.$root.app.url + 'api/transaksi/penjualan/detail/jasaservice/' + this.idDetailPenjualan, {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listPenjualanJasaService = response.data.data

                    if($.fn.dataTable.isDataTable('#tabel-detail-penjualan-jasa-service-' + this.index)) {
                        $('#tabel-detail-penjualan-jasa-service-' + this.index).DataTable().destroy()
                    }
                }
            })
        },
        deletePenjualanJasaService(id) {
            if(confirm("Apakah Anda ingin melanjutkan untuk menghapus jasa service ini?")) {
                axios.delete(this.$root.app.url + 'api/transaksi/penjualan/detail/jasaservice/data/' + id, { 
                    data: {
                        api_key: this.$root.api_key
                    } 
                })
                .then(response => {
                    if(response.data.error == false) {
                        alert(response.data.message)
                        this.getPenjualanJasaService()
                    }
                })
            }
        },
        openForm(action, index = null) {
            this.formAction = action
            if(index != null) {
                this.selectedPenjualanJasaService = this.listPenjualanJasaService[index]
            }
            this.showFormTambahUbah = true 
        },
        closeForm(reloadList) {
            this.formAction = null
            this.selectedPenjualanJasaService = null
            this.showFormTambahUbah = false

            if(reloadList) {
                this.getPenjualanJasaService()
            }
        },
    },
    created() {
        this.getPenjualanJasaService()
    },
    updated() {
        this.$nextTick(function () {
            if(!$.fn.dataTable.isDataTable('#tabel-detail-penjualan-jasa-service-' + this.index)) {
                $('#tabel-detail-penjualan-jasa-service-' + this.index).DataTable({
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