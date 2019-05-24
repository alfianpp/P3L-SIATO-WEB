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

                            <h4 class="text-center"><b>LAPORAN PENDAPATAN BULANAN</b></h4>

                            <form class="form-inline" style="margin: 20px 0px 10px 0px;">
                                <div class="form-group">
                                    <label>Tahun : </label>
                                    <select v-model="tahun" @change="getLaporan" class="no-print">
                                        <option value="null" disabled>Tahun</option>
                                        <option v-for="(tahun, index) in availableTahun" v-bind:key="index">{{tahun}}</option>
                                    </select>
                                    <p id="hide-in-view">{{tahun}}</p>
                                </div>
                            </form>

                            <table v-if="laporan != null" class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th class="text-center">No</th>
                                        <th class="text-center">Bulan</th>
                                        <th class="text-center">Service</th>
                                        <th class="text-center">Spareparts</th>
                                        <th class="text-center">Total</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr v-for="(detail, index) in laporan" v-bind:key="index">
                                        <td class="text-center">{{index+1}}</td>
                                        <td>{{detail.bulan | toNamaBulan}}</td>
                                        <td class="text-right">{{detail.jasa_service | toNumber}}</td>
                                        <td class="text-right">{{detail.spareparts | toNumber}}</td>
                                        <td class="text-right">{{detail.total | toNumber}}</td>
                                    </tr>
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td class="text-right" style="vertical-align: middle;">TOTAL</td>
                                        <td class="text-right"><h4><b>{{totalPendapatan | toNumber}}</b></h4></td>
                                    </tr>
                                </tbody>
                            </table>

                            <p id="hide-in-view" class="pull-right" style="margin-top: 25px">dicetak tanggal {{dateNow}}</p>
                            
                            <div v-if="laporan != null" class="chart" style="margin-top:25px;">
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
            availableTahun: null,
        }
    },
    methods: {
        getAvailableTahun() {
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
        },
        getLaporan() {
            axios.post(this.$root.app.url + 'api/laporan/pendapatan_bulanan', {
                tahun: this.tahun,
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
        this.getAvailableTahun()
    },
    updated() {
        this.$nextTick(function () {
            if(this.laporan != null) {
                var jasa_service = []
                var spareparts = []
                var total = []

                this.laporan.forEach(function(element) {
                    jasa_service.push((element.jasa_service) ? element.jasa_service : 0)
                    spareparts.push((element.spareparts) ? element.spareparts : 0)
                    total.push((element.total) ? element.total : 0)
                });

                var chartData = {
                    labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                    datasets: [
                        {
                            label: 'Jasa Service',
                            backgroundColor: '#e67e22',
                            borderColor: '#d35400',
                            borderWidth: 1,
                            data: jasa_service
                        },
                        {
                            label: 'Spareparts',
                            backgroundColor: '#3498db',
                            borderColor: '#2980b9',
                            borderWidth: 1,
                            data: spareparts
                        },
                        {
                            label: 'Total',
                            backgroundColor: '#95a5a6',
                            borderColor: '#7f8c8d',
                            borderWidth: 1,
                            data: total
                        }
                    ]
                }

                var ctx = document.getElementById('myChart').getContext('2d');
                var myChart = new Chart(ctx, {
                    type: 'bar',
                    data: chartData,
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top',
                        },
                        title: {
                            display: true,
                            text: 'PENDAPATAN BULANAN TAHUN ' + this.tahun
                        }
                    }
                })
            }
        })
    }
}
</script>

<style scoped>
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
}
</style>
