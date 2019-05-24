<template>
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Pembayaran</h1>
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
                                        <th>Subtotal</th>
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
                                        <td>{{ penjualan.subtotal | toCurrency }}</td>
                                        <td>{{ penjualan.tgl_transaksi }}</td>
                                        <td>{{ penjualan.status | statusPenjualan }}</td>
                                        <td class="pull-right">
                                            <a :href="'/admin/transaksi/pembayaran/detail/' + penjualan.id" class="btn btn-warning btn-sm"><i class="fa fa-eye"></i> Detail</a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import moment from 'moment'

export default {
    data: function() {
        return {
            listPenjualan: null,
        }
    },
    methods: {
        getAllPenjualan() {
            axios.post(this.$root.app.url + 'api/transaksi/pembayaran/index', {
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
                        {"type": "num-fmt", "targets": [3]},
                        {"orderable": false, "targets": [0, 2, 6]},
                        {"searchable": false, "targets": [6]}
                    ],
                })
            }
        })
    },
}
</script>