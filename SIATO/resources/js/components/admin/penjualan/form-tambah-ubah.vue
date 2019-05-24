<template>
    <div class="modal fade" id="form-tambah-ubah" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Transaksi</h4>
                    <h4 v-else-if="formAction == 'UBAH'" class="modal-title">Ubah Transaksi</h4>
                </div>

                <div class="modal-body">
                    <div v-if="response.error == true" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan</h4>
                        {{ response.message }}
                    </div>
                    <form class="form-horizontal">
                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.jenis}">
                            <label class="col-sm-3 control-label">Jenis Transaksi</label>
                            <div class="col-sm-9">
                                <select v-model="penjualan.jenis" :disabled="penjualan.jenis == 'SP'" class="form-control">
                                    <option value="null" disabled>Pilih jenis transaksi</option>
                                    <option value="SV">Service</option>
                                    <option v-if="penjualan.jenis == null || penjualan.jenis == 'SP'" value="SP">Spareparts</option>
                                    <option value="SS">Service & Spareparts</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.jenis" class="help-block">{{ response.data.jenis[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.id_cabang}">
                            <label class="col-sm-3 control-label">Cabang</label>
                            <div class="col-sm-9">
                                <select v-model="penjualan.cabang.id" class="form-control">
                                    <option value="null" disabled>Pilih cabang</option>
                                    <option v-for="(cabang, index) in listCabang" v-bind:key="index" v-bind:value="cabang.id">{{ cabang.nama }}</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.id_cabang" class="help-block">{{ response.data.id_cabang[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.id_konsumen}">
                            <label class="col-sm-3 control-label">Konsumen</label>
                            <div class="col-sm-9">
                                <select v-model="penjualan.konsumen.id" :disabled="formAction == 'UBAH'" class="form-control">
                                    <option value="null" disabled>Pilih konsumen</option>
                                    <option v-for="(konsumen, index) in listKonsumen" v-bind:key="index" v-bind:value="konsumen.id">{{ konsumen.nama }}</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.id_konsumen" class="help-block">{{ response.data.id_konsumen[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addPenjualan()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updatePenjualan()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['formAction', 'selectedPenjualan'],
    data: function() {
        return {
            penjualan: {
                id: null,
                cabang: {
                    id: null,
                    nama: null
                },
                jenis: null,
                konsumen: {
                    id: null,
                    nama: null,
                    nomor_telepon: null,
                    alamat: null
                },
                subtotal: null,
                diskon: null,
                total: null,
                uang_diterima: null,
                cs: {
                    id: null,
                    nama: null
                },
                kasir: null,
                status: null,
                tgl_transaksi: null
            },
            listCabang: null,
            listKonsumen: null,
            response: {
                error: false,
                message: '',
                data: null
            },
            reloadList: false,
        }
    },
    methods: {
        getCabang() {
            axios.post(this.$root.app.url + 'api/data/cabang/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    this.listCabang = this.response.data
                }
            })
        },
        getKonsumen() {
            axios.post(this.$root.app.url + 'api/data/konsumen/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    this.listKonsumen = this.response.data
                }
            })
        },
        addPenjualan() {
            axios.post(this.$root.app.url + 'api/transaksi/penjualan/data', {
                id_cabang: this.penjualan.cabang.id,
                jenis: this.penjualan.jenis,
                id_konsumen: this.penjualan.konsumen.id,
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
        updatePenjualan() {
            axios.put(this.$root.app.url + 'api/transaksi/penjualan/data/' + this.penjualan.id, {
                id_cabang: this.penjualan.cabang.id,
                jenis: this.penjualan.jenis,
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
            this.penjualan.id = null
            this.penjualan.cabang.id = null
            this.penjualan.cabang.nama = null
            this.penjualan.jenis = null
            this.penjualan.konsumen.id = null
            this.penjualan.konsumen.nama = null
            this.penjualan.konsumen.nomor_telepon = null
            this.penjualan.konsumen.alamat = null
            this.penjualan.subtotal = null
            this.penjualan.diskon = null
            this.penjualan.total = null
            this.penjualan.uang_diterima = null
            this.penjualan.cs.id = null
            this.penjualan.cs.nama = null
            this.penjualan.kasir = null
            this.penjualan.status = null
            this.penjualan.tgl_transaksi = null

            this.listCabang = null
            this.listKonsumen = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        this.getCabang()
        this.getKonsumen()
        if(this.selectedPenjualan != null) {
            this.penjualan.id = this.selectedPenjualan.id
            this.penjualan.cabang.id = this.selectedPenjualan.cabang.id
            this.penjualan.cabang.nama = this.selectedPenjualan.cabang.nama
            this.penjualan.jenis = this.selectedPenjualan.jenis
            this.penjualan.konsumen.id = this.selectedPenjualan.konsumen.id
            this.penjualan.konsumen.nama = this.selectedPenjualan.konsumen.nama
            this.penjualan.konsumen.nomor_telepon = this.selectedPenjualan.konsumen.nomor_telepon
            this.penjualan.konsumen.alamat = this.selectedPenjualan.konsumen.alamat
            this.penjualan.subtotal = this.selectedPenjualan.subtotal
            this.penjualan.diskon = this.selectedPenjualan.diskon
            this.penjualan.total = this.selectedPenjualan.total
            this.penjualan.uang_diterima = this.selectedPenjualan.uang_diterima
            this.penjualan.cs.id = this.selectedPenjualan.cs.id
            this.penjualan.cs.nama = this.selectedPenjualan.cs.nama
            this.penjualan.kasir = this.selectedPenjualan.kasir
            this.penjualan.status = this.selectedPenjualan.status
            this.penjualan.tgl_transaksi = this.selectedPenjualan.tgl_transaksi
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>