<template>
    <div class="modal fade" id="form-tambah-ubah" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Kendaraan</h4>
                    <h4 v-else-if="formAction == 'UBAH'" class="modal-title">Ubah Transaksi</h4>
                </div>

                <div class="modal-body">
                    <div v-if="response.error == true" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan</h4>
                        {{ response.message }}
                    </div>
                    <form class="form-horizontal">
                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.nomor_polisi}">
                            <label class="col-sm-3 control-label">Kendaraan</label>
                            <div class="col-sm-9">
                                <select v-model="detailPenjualan.kendaraan.nomor_polisi" :disabled="formAction == 'UBAH'" class="form-control">
                                    <option value="null" disabled>Pilih kendaraan</option>
                                    <option v-for="(kendaraan, index) in listKendaraan" v-bind:key="index" v-bind:value="kendaraan.nomor_polisi">{{ kendaraan.merk }} {{ kendaraan.tipe }} {{ kendaraan.nomor_polisi }}</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.nomor_polisi" class="help-block">{{ response.data.nomor_polisi[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.id_montir}">
                            <label class="col-sm-3 control-label">Montir</label>
                            <div class="col-sm-9">
                                <select v-model="detailPenjualan.montir.id" class="form-control">
                                    <option value="null" disabled>Pilih montir</option>
                                    <option v-for="(montir, index) in listMontir" v-bind:key="index" v-bind:value="montir.id">{{ montir.nama }}</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.id_montir" class="help-block">{{ response.data.id_montir[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addDetailPenjualan()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updateDetailPenjualan()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['idPenjualan', 'idKonsumen', 'formAction', 'selectedDetailPenjualan'],
    data: function() {
        return {
            detailPenjualan: {
                id: null,
                id_penjualan: null,
                kendaraan: {
                    nomor_polisi: null,
                    merk: null,
                    tipe: null
                },
                montir: {
                    id: null,
                    nama: null
                }
            },
            listKendaraan: null,
            listMontir: null,
            response: {
                error: false,
                message: '',
                data: null
            },
            reloadList: false,
        }
    },
    methods: {
        getKendaraan() {
            axios.post(this.$root.app.url + 'api/data/kendaraan/index/id_pemilik/' + this.idKonsumen, {
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    this.listKendaraan = this.response.data
                }
            })
        },
        getMontir() {
            axios.post(this.$root.app.url + 'api/data/pegawai/index/role/3', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    this.listMontir = this.response.data
                }
            })
        },
        addDetailPenjualan() {
            axios.post(this.$root.app.url + 'api/transaksi/penjualan/detail', {
                id_penjualan: this.idPenjualan,
                nomor_polisi: this.detailPenjualan.kendaraan.nomor_polisi,
                id_montir: this.detailPenjualan.montir.id,
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
        updateDetailPenjualan() {
            axios.put(this.$root.app.url + 'api/transaksi/penjualan/detail/' + this.detailPenjualan.id, {
                id_montir: this.detailPenjualan.montir.id,
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
            this.detailPenjualan.id = null
            this.detailPenjualan.id_penjualan = null
            this.detailPenjualan.kendaraan.nomor_polisi = null
            this.detailPenjualan.kendaraan.merk = null
            this.detailPenjualan.kendaraan.tipe = null
            this.detailPenjualan.montir.id = null
            this.detailPenjualan.montir.nama = null

            this.listKendaraan = null
            this.listMontir = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        this.getKendaraan()
        this.getMontir()
        if(this.selectedDetailPenjualan != null) {
            this.detailPenjualan.id = this.selectedDetailPenjualan.id
            this.detailPenjualan.id_penjualan = this.selectedDetailPenjualan.id_penjualan
            this.detailPenjualan.kendaraan.nomor_polisi = this.selectedDetailPenjualan.kendaraan.nomor_polisi
            this.detailPenjualan.kendaraan.merk = this.selectedDetailPenjualan.kendaraan.merk
            this.detailPenjualan.kendaraan.tipe = this.selectedDetailPenjualan.kendaraan.tipe
            this.detailPenjualan.montir.id = this.selectedDetailPenjualan.montir.id
            this.detailPenjualan.montir.nama = this.selectedDetailPenjualan.montir.nama
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>