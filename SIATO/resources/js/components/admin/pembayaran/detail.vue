<template>
    <div class="content-wrapper">
        <section v-if="detailPembayaran != null" class="content-header">
            <h1>Penjualan </h1>
            <div class="pull-right" style="margin-top: 0; margin-bottom: 0; position: absolute; top: 11px; right: 15px;">
                <button @click="openForm()" type="button" class="btn btn-success" data-toggle="modal" data-target="#form-bayar"><i class="fa fa-check"></i> Bayar</button>
            </div>
        </section>
        
        <section v-if="detailPembayaran != null" class="invoice">
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        {{ detailPembayaran.nomor_transaksi }}
                        <small class="pull-right">{{ detailPembayaran.tgl_transaksi }}</small>
                    </h2>
                </div>
            </div>

            <div class="row invoice-info">
                <div class="col-sm-4 invoice-col">
                    <div class="row">
                        <div class="col-sm-3">
                            Cust<br>
                            Telepon
                        </div>

                        <div class="col-sm-9">
                            <b>{{detailPembayaran.konsumen.nama}}</b><br>
                            <b>{{detailPembayaran.konsumen.nomor_telepon}}</b>
                        </div>
                    </div>
                </div>

                <div class="col-sm-4 invoice-col">
                    <div class="row">
                        <div class="col-sm-3">
                            CS<br>
                            Montir
                        </div>

                        <div class="col-sm-9">
                            <b>{{detailPembayaran.cs.nama}}</b><br>
                            <b v-for="(montir, index) in detailPembayaran.montir" v-bind:key="index">
                                {{montir.nama}}<br/>
                            </b>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table row -->
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <hr>
                    <p class="lead text-center">Spareparts</p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Merk</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(spareparts, index) in detailPembayaran.spareparts" v-bind:key="index">
                                <td>{{spareparts.spareparts.kode}}</td>
                                <td>{{spareparts.spareparts.nama}}</td>
                                <td>{{spareparts.spareparts.merk}}</td>
                                <td>{{spareparts.harga | toCurrency}}</td>
                                <td>{{spareparts.jumlah}}</td>
                                <td>{{spareparts.jumlah*spareparts.harga | toCurrency}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{detailPembayaran.subtotal.spareparts | toCurrency}}</td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>
                    <p class="lead text-center">Jasa Service</p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Harga</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="(jasa_service, index) in detailPembayaran.jasa_service" v-bind:key="index">
                                <td>{{jasa_service.jasa_service.nama}}</td>
                                <td>{{jasa_service.harga | toCurrency}}</td>
                                <td>{{jasa_service.jumlah}}</td>
                                <td>{{jasa_service.jumlah*jasa_service.harga | toCurrency}}</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td>{{detailPembayaran.subtotal.jasa_service | toCurrency}}</td>
                            </tr>
                        </tbody>
                    </table>
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
            formAction: null,
            selectedDetailPenjualan: null,
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
        closeForm() {
            this.showForm = false
        },
        print() {

        },
    },
    created() {
        this.getDetailPembayaran()
    },
}
</script>