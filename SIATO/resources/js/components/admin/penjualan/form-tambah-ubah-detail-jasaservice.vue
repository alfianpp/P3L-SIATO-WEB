<template>
    <div class="modal fade" id="form-tambah-ubah" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Jasa Service</h4>
                </div>

                <div class="modal-body">
                    <div v-if="response.error == true" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan</h4>
                        {{ response.message }}
                    </div>
                    <form class="form-horizontal">
                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.id_jasaservice}">
                            <label class="col-sm-3 control-label">Jasa Service</label>
                            <div class="col-sm-9">
                                <select v-model="penjualanJasaService.jasa_service.id" class="form-control">
                                    <option value="null" disabled>Pilih jasa service</option>
                                    <option v-for="(jasa_service, index) in listJasaService" v-bind:key="index" v-bind:value="jasa_service.id">{{ jasa_service.nama }}</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.id_jasaservice" class="help-block">{{ response.data.id_jasaservice[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addPenjualanJasaService()" type="button" class="btn btn-success">Tambah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['idDetailPenjualan', 'formAction', 'selectedPenjualanJasaService'],
    data: function() {
        return {
            penjualanJasaService: {
                id: null,
                id_detail_penjualan: null,
                jasa_service: {
                    id: null,
                    nama: null
                },
                jumlah: null,
                harga: null
            },
            listJasaService: null,
            response: {
                error: false,
                message: '',
                data: null
            },
            reloadList: false,
        }
    },
    methods: {
        getJasaService() {
            axios.post(this.$root.app.url + 'api/data/jasaservice/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    this.listJasaService = this.response.data
                }
            })
        },
        addPenjualanJasaService() {
            axios.post(this.$root.app.url + 'api/transaksi/penjualan/detail/jasaservice/data', {
                id_detail_penjualan: this.idDetailPenjualan,
                id_jasaservice: this.penjualanJasaService.jasa_service.id,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reloadList = true
                    $('#form-tambah-ubah').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        close() {
            this.penjualanJasaService.id = null
            this.penjualanJasaService.id_detail_penjualan = null
            this.penjualanJasaService.jasa_service.id = null
            this.penjualanJasaService.jasa_service.nama = null
            this.penjualanJasaService.jumlah = null
            this.penjualanJasaService.harga = null

            this.listJasaService = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        this.getJasaService()
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>