<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Riwayat Transaksi</h1>
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
                                        <th>Tanggal Transaksi</th>
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                
                                <tbody>
                                    <tr v-for="(penjualan, index) in listPenjualan" v-bind:key="index">
                                        <td>{{ penjualan.jenis + '|' + penjualan.tgl_transaksi + '|' + penjualan.id | nomorTransaksi }}</td>
                                        <td>{{ penjualan.konsumen.nama }}</td>
                                        <td>{{ penjualan.konsumen.nomor_telepon }}</td>
                                        <td>{{ penjualan.tgl_transaksi }}</td>
                                        <td>{{ penjualan.status | statusTransaksi }}</td>
                                        <td class="pull-right">
                                            <a :href="'/admin/transaksi/penjualan/detail/' + penjualan.id" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i> Detail</a>
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
import data from '../admin/penjualan/form-tambah-ubah.vue';
import moment from 'moment'

export default {
    components: {
      data
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
    filters: {
        nomorTransaksi: function (value) {
            var temp = value.split("|")
            return temp[0] + "-" + moment(String(temp[1])).format('DDMMYY') + "-" + temp[2]
        },
        statusTransaksi: function (value) {
            switch(value) {
                case 1:
                    return "Terbuka"
                    break
                case 2:
                    return "Menunggu pembayaran"
                    break
                case 3:
                    return "Selesai"
                    break
            }
        }
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
                    'ordering'    : false,
                    'paging'      : true,
                    'searching'   : true,
                    'columnDefs': [
                        {"searchable": false, "targets": [1, 2, 3, 4, 5]}
                    ],
                })
            }
        })
    },
}
</script>