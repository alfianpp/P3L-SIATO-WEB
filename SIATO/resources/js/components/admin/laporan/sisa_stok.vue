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

                            <h4 class="text-center"><b>LAPORAN SISA STOK</b></h4>

                            <form class="form-inline" style="margin: 20px 0px 10px 0px;">
                                <div class="form-group">
                                    <label>Tahun : </label>
                                    <select v-model="tahun" @change="getLaporan" class="no-print">
                                        <option value="null" disabled>Tahun</option>
                                        <option v-for="(tahun, index) in availableTahun" v-bind:key="index">{{tahun}}</option>
                                    </select>
                                    <p id="hide">{{tahun}}</p>
                                </div>

                                <div class="form-group">
                                    <label>Tipe Barang : </label>
                                    <select v-model="tipe_barang" @change="getLaporan" class="no-print">
                                        <option value="null" disabled>Tipe Barang</option>
                                        <option v-for="(tipe, index) in availableTipe" v-bind:key="index">{{tipe}}</option>
                                    </select>
                                    <p id="hide">{{tipe_barang}}</p>
                                </div>
                            </form>

                            <table v-if="tahun != null && tipe_barang != null" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Bulan</th>
                                        <th class="text-center">Sisa Stok</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(asd, index) in laporan" v-bind:key="index">
                                        <td class="text-center">{{index+1}}</td>
                                        <td>{{asd.bulan | toNamaBulan}}</td>
                                        <td class="text-right">{{asd.sisa_stok | toNumber}}</td>
                                    </tr>
                                </tbody>
                            </table>
                            <p id="hide" class="pull-right" style="margin-top: 25px">dicetak tanggal {{dateNow}}</p>
                            
                            <div v-if="tahun != null && tipe_barang != null" class="chart" style="margin-top:25px;">
                                <canvas id="myChart" style="height:300px"></canvas>
                            </div>
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
            tipe_barang: null,
            availableTahun: null,
            availableTipe: null,
        }
    },
    methods: {
        getLaporan() {
            axios.post(this.$root.app.url + 'api/laporan/sisa_stok', {
                tahun: this.tahun,
                tipe_barang: this.tipe_barang,
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.laporan = response.data.data

                    $("canvas#myChart").remove();
                    $("div.chart").append('<canvas id="myChart" style="height:300px"></canvas>');
                }
            })
        },
        getAvailableTahunAndTipe() {
            axios.get(this.$root.app.url + 'api/transaksi/penjualan/index/tgl_transaksi')
            .then(response => {
                var _temp = []

                response.data.data.forEach(function(tgl_transaksi) {
                    var tahun = moment(tgl_transaksi, 'DD-MM-YYYY').format('YYYY')

                    if(!_temp.includes(tahun)) {
                        _temp.push(tahun)
                    }
                })

                this.availableTahun = _temp
            })

            axios.get(this.$root.app.url + 'api/data/spareparts/index/tipe')
            .then(response => {
                this.availableTipe = response.data.data
            })
        },
        print() {
            window.print()
        },
    },
    filters: {
        toNamaBulan: function (value) {
            return moment(value, 'MM').format('MMMM');
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
        this.getAvailableTahunAndTipe()
        this.getLaporan()
    },
    updated() {
        this.$nextTick(function () {
            if(this.tahun != null && this.tipe_barang != null) {
                var sisa_stok = []

                this.laporan.forEach(function(element) {
                    sisa_stok.push((element.sisa_stok) ? element.sisa_stok : 0)
                });

                var chartData = {
                    labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    datasets: [
                        {
                            label: 'Sisa Stok',
                            fill: false,
                            backgroundColor: '#3498db',
                            borderColor: '#2980b9',
                            borderWidth: 2,
                            data: sisa_stok
                        }
                    ]
                }

                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'line',
                    data: chartData,
                    options: {
                        responsive: true,
                        legend: false,
                        title: {
                            display: true,
                            text: 'Sisa Stok ' + this.tipe_barang + ' Tahun ' + this.tahun
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                    }
                })

                myChart.update();
            }
        })
    }
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

    .form-group {
        margin-bottom: 0px;
    }
}
</style>
