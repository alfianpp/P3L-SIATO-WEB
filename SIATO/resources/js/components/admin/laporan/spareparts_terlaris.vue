<template>
    <div class="content-wrapper">
        <section class="content-header"></section>

        <section class="content">
            <div class="row">
                <div class="col-xs-10 col-xs-offset-1">
                    <div class="box">
                        <div class="box-header no-print">
                            <button @click="print()" type="button" class="btn btn-sm btn-default pull-right"><i class="fa fa-print"></i> Print</button>
                        </div>

                        <div class="box-body">
                            <div id="hide" class="row">
                                <div class="col-xs-10 col-xs-offset-1">
                                    <div class="row">
                                        <div class="col-xs-4"><img :src="$root.app.url + 'images/logo.png'" class="img-responsive" alt="logo"></div>
                                        <div class="col-xs-8 text-center">
                                            <h1><b>A</b>TM<b>A</b> <b>A</b>UTO</h1>
                                            <h5>MOTORCYCLE SPAREPARTS AND SERVICES</h5>
                                            <p>
                                                Jl. Babarsari N o. 43 Yogyakarta 552181
                                                Telp. (0274) 487711
                                                http://www.atmaauto.com
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4 class="text-center"><b>LAPORAN SPAREPARTS TERLARIS</b></h4>

                            <form class="form-inline" style="margin: 20px 0px 10px 0px;">
                                <div class="form-group">
                                    <label>Tahun : </label>
                                    <select v-model="tahun" class="no-print">
                                        <option>2019</option>
                                    </select>
                                    <p id="hide">2019</p>
                                </div>
                            </form>

                            <table v-if="laporan != null" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Bulan</th>
                                        <th class="text-center">Nama Barang</th>
                                        <th class="text-center">Tipe Barang</th>
                                        <th class="text-center">Jumlah Penjualan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(asd, index) in laporan" v-bind:key="index">
                                        <td class="text-center">{{index+1}}</td>
                                        <td>{{asd.bulan | toNamaBulan}}</td>
                                        <td>{{asd.nama_barang | textNull}}</td>
                                        <td>{{asd.tipe_barang | textNull}}</td>
                                        <td class="text-right">{{asd.jumlah_penjualan | toNumber}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p id="hide" class="pull-right" style="margin-top: 25px">dicetak tanggal {{dateNow}}</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<script>
import Chart from 'chart.js'
import moment from 'moment'
import 'moment/locale/id'
moment.locale('id')

export default {
    data: function() {
        return {
            laporan: null,
            tahun: 2019,
        }
    },
    methods: {
        getLaporan() {
            axios.post(this.$root.app.url + 'api/laporan/spareparts_terlaris', {
                tahun: 2019,
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.laporan = response.data.data
                }
            })
        },
        print() {
            window.print()
        },
    },
    filters: {
        toNamaBulan: function (value) {
            return moment(value, 'MM').format('MMMM');
        },
        textNull: function (value) {
            return (value) ? value : '-'
        }
    },
    computed: {
        totalPendapatan: function () {
            var total = 0

            this.laporan.forEach(function(pendapatan_bulanan) {
                total += pendapatan_bulanan.total
            });

            return total
        },
        dateNow: function () {
            return moment().format('DD MMMM YYYY')
        },
    },
    created() {
        this.getLaporan()
    },
}
</script>

<style>
#hide {
    display: none;
}

@media print {
    .box {
        border-top: none;
    }

    #hide {
        display: inline;
    }
}
</style>
