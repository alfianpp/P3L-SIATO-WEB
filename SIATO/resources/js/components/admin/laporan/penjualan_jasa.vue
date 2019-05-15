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
                            <div id="hide-in-view" class="row">
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

                            <h4 class="text-center"><b>LAPORAN PENJUALAN JASA</b></h4>

                            <form class="form-inline" style="margin: 20px 0px 10px 0px;">
                                <div class="form-group">
                                    <label>Tahun : </label>
                                    <select v-model="tahun" @change="getLaporan" class="no-print">
                                        <option value="null" disabled>Tahun</option>
                                        <option v-for="(tahun, index) in availableTahun" v-bind:key="index">{{tahun}}</option>
                                    </select>
                                    <p id="hide-in-view">{{tahun}}</p>
                                </div>

                                <div class="form-group">
                                    <label>Bulan : </label>
                                    <select v-model="bulan" @change="getLaporan" class="no-print">
                                        <option value="null" disabled>Bulan</option>
                                        <option v-for="(bulan, index) in availableBulan" v-bind:key="index" v-bind:value="bulan.id">{{bulan.nama}}</option>
                                    </select>
                                    <p id="hide-in-view">{{bulan | toMonthName}}</p>
                                </div>
                            </form>

                            <table v-if="laporan != null" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Merk</th>
                                        <th class="text-center">Tipe Motor</th>
                                        <th class="text-center">Nama Service</th>
                                        <th class="text-center">Jumlah Penjualan</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(detail, index) in laporan" v-bind:key="index">
                                        <td class="text-center">{{index+1}}</td>
                                        <td>{{detail.merk}}</td>
                                        <td>{{detail.tipe}}</td>
                                        <td>{{detail.nama_service}}</td>
                                        <td class="text-right">{{detail.jumlah_penjualan | toNumber}}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <p id="hide-in-view" class="pull-right" style="margin-top: 25px">dicetak tanggal {{dateNow}}</p>
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
            tahun: null,
            bulan: null,
            availableTahun: null,
            availableBulan: null,
        }
    },
    methods: {
        getAvailableTahunAndBulan() {
            axios.get(this.$root.app.url + 'api/transaksi/penjualan/index/tgl_transaksi')
            .then(response => {
                var _temp_tahun = []
                var _temp_bulan = []
                var _temp_nama_bulan = []
                var _temp_nomor_bulan = []

                response.data.data.forEach(function(tgl_transaksi) {
                    var tahun = moment(tgl_transaksi, 'DD-MM-YYYY').format('YYYY')
                    var nama_bulan = moment(tgl_transaksi, 'DD-MM-YYYY').format('MMMM')
                    var nomor_bulan = moment(tgl_transaksi, 'DD-MM-YYYY').format('M')

                    if(!_temp_tahun.includes(tahun)) {
                        _temp_tahun.push(tahun)
                    }

                    if(!_temp_nama_bulan.includes(nama_bulan) && !_temp_nomor_bulan.includes(nomor_bulan)) {
                        _temp_nama_bulan.push(nama_bulan)
                        _temp_nomor_bulan.push(nomor_bulan)
                        var test = {
                            'id': nomor_bulan,
                            'nama': nama_bulan
                        }
                        _temp_bulan.push(test);
                    }
                })

                this.availableTahun = _temp_tahun
                this.availableBulan = _temp_bulan
            })
        },
        getLaporan() {
            if(this.tahun != null && this.bulan != null) {
                axios.post(this.$root.app.url + 'api/laporan/penjualan_jasa', {
                    tahun: this.tahun,
                    bulan: this.bulan,
                    api_key: this.$root.api_key,
                })
                .then(response => {
                    if(response.data.error == false) {
                        this.laporan = response.data.data

                        $("canvas#myChart").remove();
                        $("div.chart").append('<canvas id="myChart" style="height:300px"></canvas>');
                    }
                })
            }
        },
        print() {
            window.print()
        },
    },
    filters: {
        toMonthName: function (value) {
            return moment(value, 'MM').format('MMMM');
        }
    },
    computed: {
        dateNow: function () {
            return moment().format('DD MMMM YYYY')
        },
    },
    created() {
        this.getAvailableTahunAndBulan()
    },
}
</script>

<style>
#hide-in-view {
    display: none;
}

@media print {
    .box {
        border-top: none;
    }

    #hide-in-view {
        display: inline;
    }

    .form-group {
        margin-bottom: 0px;
    }
}
</style>
