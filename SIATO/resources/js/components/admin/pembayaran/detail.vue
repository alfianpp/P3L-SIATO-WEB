<template>
    <div class="content-wrapper">
        <section class="content-header"></section>
        
        <section v-if="detailPembayaran != null" class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header no-print">
                            <div class="row">
                                <div class="col-xs-6">
                                    <h3>Detail Pembayaran</h3>
                                </div>

                                <div class="col-xs-6">
                                    <div class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                                        <button v-if="detailPembayaran.status == 2" @click="openForm()" type="button" class="btn btn-success" data-toggle="modal" data-target="#form-bayar"><i class="fa fa-check"></i> Bayar</button>
                                        <button v-if="detailPembayaran.status == 3" @click="print()" type="button" class="btn btn-default"><i class="fa fa-print"></i> Print</button>
                                    </div>
                                </div>
                            </div>
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

                            <div id="hide-in-view" class="row">
                                <div class="col-xs-12">
                                    <h4 class="text-center" style="margin-top: 20px; margin-bottom: 20px;"><b>NOTA LUNAS</b></h4>
                                </div>
                            </div>
                            

                            <div class="row">
                                <div class="col-xs-6">
                                    <h3>{{ detailPembayaran.nomor_transaksi }}</h3>
                                </div>

                                <div class="col-xs-6">
                                    <small class="pull-right">{{ detailPembayaran.tgl_transaksi }}</small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            Cust<br>
                                            Telepon
                                        </div>

                                        <div class="col-xs-9">
                                            {{detailPembayaran.konsumen.nama}}<br>
                                            {{detailPembayaran.konsumen.nomor_telepon}}
                                        </div>
                                    </div>                                 
                                </div>

                                <div class="col-xs-6">
                                    <div class="row">
                                        <div class="col-xs-3 col-xs-offset-3">
                                            CS<br>
                                            {{(detailPembayaran.montir.length > 0) ? 'Montir' : ''}}
                                        </div>

                                        <div class="col-xs-6">
                                            {{detailPembayaran.cs.nama}}<br>
                                            <div v-for="(montir, index) in detailPembayaran.montir" v-bind:key="index">
                                                {{montir.nama}}<br/>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                            </div>

                            <div v-if="detailPembayaran.spareparts.length > 0">
                                <hr style="margin-bottom: 10px;">
                                <h4 class="text-center" style="margin-top: 0px; margin-bottom: 0px;"><b>SPAREPARTS</b></h4>
                                <hr style="margin-top: 10px;">

                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Kode</th>
                                            <th>Nama</th>
                                            <th>Merk</th>
                                            <th class="text-right">Harga</th>
                                            <th class="text-right">Jumlah</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(spareparts, index) in detailPembayaran.spareparts" v-bind:key="index">
                                            <td>{{spareparts.spareparts.kode}}</td>
                                            <td>{{spareparts.spareparts.nama}}</td>
                                            <td>{{spareparts.spareparts.merk}}</td>
                                            <td class="text-right">{{spareparts.harga | toCurrency}}</td>
                                            <td class="text-right">{{spareparts.jumlah}}</td>
                                            <td class="text-right">{{spareparts.jumlah*spareparts.harga | toCurrency}}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right">{{detailPembayaran.subtotal.spareparts | toCurrency}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div v-if="detailPembayaran.jasa_service.length > 0">
                                <hr style="margin-bottom: 10px;">
                                <h4 class="text-center" style="margin-top: 0px; margin-bottom: 0px;"><b>JASA SERVICE</b></h4>
                                <hr style="margin-top: 10px;">

                                <div style="margin-bottom:15px;">
                                    <p v-for="(kendaraan, index) in detailPembayaran.kendaraan" v-bind:key="index">
                                        {{kendaraan.merk}} {{kendaraan.tipe}} {{kendaraan.nomor_polisi}}<br>
                                    </p>
                                </div>
                                
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Nama</th>
                                            <th></th>
                                            <th></th>
                                            <th class="text-right">Harga</th>
                                            <th class="text-right">Jumlah</th>
                                            <th class="text-right">Subtotal</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr v-for="(jasa_service, index) in detailPembayaran.jasa_service" v-bind:key="index">
                                            <td>{{jasa_service.jasa_service.nama}}</td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right">{{jasa_service.harga | toCurrency}}</td>
                                            <td class="text-right">{{jasa_service.jumlah}}</td>
                                            <td class="text-right">{{jasa_service.jumlah*jasa_service.harga | toCurrency}}</td>
                                        </tr>
                                        <tr>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td class="text-right"></td>
                                            <td class="text-right"></td>
                                            <td class="text-right">{{detailPembayaran.subtotal.jasa_service | toCurrency}}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-xs-3">
                                    <div id="hide-in-view">
                                        Cust<br><br><br><br><br>
                                        ({{detailPembayaran.konsumen.nama}})
                                    </div>
                                </div>

                                <div class="col-xs-3">
                                    <div v-if="detailPembayaran.status == 3" id="hide-in-view">
                                        Kasir<br><br><br><br><br>
                                        ({{detailPembayaran.kasir.nama}})
                                    </div>
                                </div>

                                <div class="col-xs-3 col-xs-offset-3">
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <table class="table table-sm">
                                                <thead></thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Subtotal</td>
                                                        <td class="text-right">{{detailPembayaran.subtotal.penjualan | toCurrency}}</td>
                                                    </tr>
                                                    <tr v-if="detailPembayaran.status == 3">
                                                        <td>Diskon</td>
                                                        <td class="text-right">{{detailPembayaran.diskon | toCurrency}}</td>
                                                    </tr>
                                                    <tr v-if="detailPembayaran.status == 3">
                                                        <td style="vertical-align:middle;">TOTAL</td>
                                                        <td class="text-right"><h4><b>{{detailPembayaran.total | toCurrency}}</b></h4></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <form-bayar 
        v-if="showForm == true" 
        v-bind:id-penjualan="idPenjualan"
        v-bind:subtotal-penjualan="detailPembayaran.subtotal.penjualan"
        v-on:close="closeForm">
        </form-bayar>
    </div>
</template>

<script>
import formBayar from './form-bayar.vue';

export default {
    components: {
      formBayar
    },
    props: ['idPenjualan'],
    data: function() {
        return {
            detailPembayaran: null,
            showForm: false,
        }
    },
    methods: {
        getDetailPembayaran() {
            axios.post(this.$root.app.url + 'api/transaksi/pembayaran/detail/' + this.idPenjualan, {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.detailPembayaran = response.data.data
                }
            })
        },
        openForm() {
            this.showForm = true 
        },
        closeForm(reload) {
            this.showForm = false

            if(reload) {
                window.location.reload()
            }
        },
        print() {
            window.print()
        },
    },
    created() {
        this.getDetailPembayaran()
    },
}
</script>

<style scoped>
.table {
    table-layout: fixed;
}

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
