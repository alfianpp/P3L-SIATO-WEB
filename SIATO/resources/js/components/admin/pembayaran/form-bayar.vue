<template>
    <div class="modal fade" id="form-bayar" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Bayar</h4>
                </div>

                <div class="modal-body">
                    <div v-if="response.error == true" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan</h4>
                        {{ response.message }}
                    </div>
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Subtotal</label>
                            <div class="col-sm-9">
                                <p class="form-control-static">{{subtotalPenjualan | toCurrency}}</p>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.diskon}">
                            <label class="col-sm-3 control-label">Diskon</label>
                            <div class="col-sm-9">
                                <money v-model="diskon" v-bind="money" class="form-control"></money>
                                <span v-if="response.error && response.data && response.data.diskon" class="help-block">{{ response.data.diskon[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label"></label>
                            <div class="col-sm-9">
                                <p class="form-control-static">Jumlah uang yang harus dibayarkan:</p>
                                <p class="lead"><b>{{totalBayar | toCurrency}}</b></p>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.uang_diterima}">
                            <label class="col-sm-3 control-label">Uang Diterima</label>
                            <div class="col-sm-9">
                                <money v-model="uang_diterima" v-bind="money" class="form-control"></money>
                                <span v-if="response.error && response.data && response.data.uang_diterima" class="help-block">{{ response.data.uang_diterima[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button @click="doPay()" type="button" class="btn btn-success">Simpan</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Money} from 'v-money'

export default {
    components: {Money},
    props: ['idPenjualan', 'subtotalPenjualan'],
    data: function() {
        return {
            diskon: 0,
            uang_diterima: 0,
            response: {
                error: false,
                message: '',
                data: null
            },
            reload: false,
            money: {
                precision: 0,
                decimal: ',',
                thousands: '.',
                prefix: 'Rp '
            }
        }
    },
    methods: {
        doPay() {
            axios.put(this.$root.app.url + 'api/transaksi/pembayaran/detail/' + this.idPenjualan, {
                diskon: this.diskon,
                uang_diterima: this.uang_diterima,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reload = true
                    $('#form-bayar').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        close() {
            this.diskon = 0
            this.uang_diterima = 0
                       
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reload)
            this.reload = false
        },
    },
    computed: {
        totalBayar: function () {
            return this.subtotalPenjualan - this.diskon
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>