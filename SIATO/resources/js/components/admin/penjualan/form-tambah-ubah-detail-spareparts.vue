<template>
    <div class="modal fade" id="form-tambah-ubah" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Spareparts</h4>
                    <h4 v-else-if="formAction == 'UBAH'" class="modal-title">Ubah Transaksi</h4>
                </div>

                <div class="modal-body">
                    <div v-if="response.error == true" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan</h4>
                        {{ response.message }}
                    </div>
                    <form class="form-horizontal">
                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.kode_spareparts}">
                            <label class="col-sm-3 control-label">Spareparts</label>
                            <div class="col-sm-9">
                                <select v-model="penjualanSpareparts.spareparts.kode" :disabled="formAction == 'UBAH'" class="form-control">
                                    <option value="null" disabled>Pilih spareparts</option>
                                    <option v-for="(spareparts, index) in listSpareparts" v-bind:key="index" v-bind:value="spareparts.kode">{{ spareparts.nama }} - {{ spareparts.merk }}</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.kode_spareparts" class="help-block">{{ response.data.kode_spareparts[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.jumlah}">
                            <label class="col-sm-3 control-label">Jumlah</label>
                            <div class="col-sm-9">
                                <input v-model="penjualanSpareparts.jumlah" type="number" class="form-control" placeholder="Jumlah">
                                <span v-if="response.error && response.data && response.data.jumlah" class="help-block">{{ response.data.jumlah[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addPenjualanSpareparts()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updatePenjualanSpareparts()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['idDetailPenjualan', 'formAction', 'selectedPenjualanSpareparts'],
    data: function() {
        return {
            penjualanSpareparts: {
                id: null,
                id_detail_penjualan: null,
                spareparts: {
                    kode: null,
                    nama: null,
                    merk: null
                },
                jumlah: null,
                harga: null
            },
            listSpareparts: null,
            response: {
                error: false,
                message: '',
                data: null
            },
            reloadList: false,
        }
    },
    methods: {
        getSpareparts() {
            axios.post(this.$root.app.url + 'api/data/spareparts/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    this.listSpareparts = this.response.data
                }
            })
        },
        addPenjualanSpareparts() {
            axios.post(this.$root.app.url + 'api/transaksi/penjualan/detail/spareparts/data', {
                id_detail_penjualan: this.idDetailPenjualan,
                kode_spareparts: this.penjualanSpareparts.spareparts.kode,
                jumlah: this.penjualanSpareparts.jumlah,
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
        updatePenjualanSpareparts() {
            axios.put(this.$root.app.url + 'api/transaksi/penjualan/detail/spareparts/data/' + this.penjualanSpareparts.id, {
                jumlah: this.penjualanSpareparts.jumlah,
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
            this.penjualanSpareparts.id = null
            this.penjualanSpareparts.id_detail_penjualan = null
            this.penjualanSpareparts.spareparts.kode = null
            this.penjualanSpareparts.spareparts.nama = null
            this.penjualanSpareparts.spareparts.merk = null
            this.penjualanSpareparts.jumlah = null
            this.penjualanSpareparts.harga = null

            this.listSpareparts = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        this.getSpareparts()
        if(this.selectedPenjualanSpareparts != null) {
            this.penjualanSpareparts.id = this.selectedPenjualanSpareparts.id
            this.penjualanSpareparts.id_detail_penjualan = this.selectedPenjualanSpareparts.id_detail_penjualan
            this.penjualanSpareparts.spareparts.kode = this.selectedPenjualanSpareparts.spareparts.kode
            this.penjualanSpareparts.spareparts.nama = this.selectedPenjualanSpareparts.spareparts.nama
            this.penjualanSpareparts.spareparts.merk = this.selectedPenjualanSpareparts.spareparts.merk
            this.penjualanSpareparts.jumlah = this.selectedPenjualanSpareparts.jumlah
            this.penjualanSpareparts.harga = this.selectedPenjualanSpareparts.harga
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>