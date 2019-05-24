<template>
    <div class="modal fade" id="form-tambah-ubah-detail-pengadaan-barang" ref="modal">
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
                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.kode_spareparts}">
                            <label class="col-sm-3 control-label">Spareparts</label>
                            <div class="col-sm-9">
                                <select v-model="detailPengadaanBarang.spareparts.kode" :disabled="formAction == 'UBAH'" class="form-control">
                                    <option value="null" disabled>Pilih spareparts</option>
                                    <option v-for="(spareparts, index) in listSpareparts" v-bind:key="index" v-bind:value="spareparts.kode">{{ spareparts.kode }} - {{ spareparts.nama }} - {{ spareparts.merk }} - Sisa stok: {{ spareparts.stok }}</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.kode_spareparts" class="help-block">{{ response.data.kode_spareparts[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.jumlah_pesan}">
                            <label class="col-sm-3 control-label">Jumlah Pesan</label>
                            <div class="col-sm-9">
                                <input v-model="detailPengadaanBarang.jumlah_pesan" type="number" class="form-control" placeholder="Jumlah Pesan">
                                <span v-if="response.error && response.data && response.data.jumlah_pesan" class="help-block">{{ response.data.jumlah_pesan[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addDetailPengadaanBarang()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updateDetailPengadaanBarang()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['idPengadaanBarang', 'formAction', 'selectedDetailPengadaanBarang'],
    data: function() {
        return {
            detailPengadaanBarang: {
                id: null,
                id_pengadaan_barang: null,
                spareparts: {
                    kode: null,
                    nama: null,
                    merk: null,
                    tipe: null
                },
                jumlah_pesan: null,
                jumlah_datang: null,
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
        addDetailPengadaanBarang() {
            axios.post(this.$root.app.url + 'api/transaksi/pengadaan/detail', {
                id_pengadaan_barang: this.idPengadaanBarang,
                kode_spareparts: this.detailPengadaanBarang.spareparts.kode,
                jumlah_pesan: this.detailPengadaanBarang.jumlah_pesan,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reloadList = true
                    $('#form-tambah-ubah-detail-pengadaan-barang').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        updateDetailPengadaanBarang() {
            axios.put(this.$root.app.url + 'api/transaksi/pengadaan/detail/' + this.detailPengadaanBarang.id, {
                jumlah_pesan: this.detailPengadaanBarang.jumlah_pesan,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reloadList = true
                    $('#form-tambah-ubah-detail-pengadaan-barang').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        close() {
            this.detailPengadaanBarang.id = null
            this.detailPengadaanBarang.id_pengadaan_barang = null
            this.detailPengadaanBarang.spareparts.kode = null
            this.detailPengadaanBarang.spareparts.nama = null
            this.detailPengadaanBarang.spareparts.merk = null
            this.detailPengadaanBarang.spareparts.tipe = null
            this.detailPengadaanBarang.jumlah_pesan = null
            this.detailPengadaanBarang.jumlah_datang = null
            this.detailPengadaanBarang.harga = null

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
        if(this.selectedDetailPengadaanBarang != null) {
            this.detailPengadaanBarang.id = this.selectedDetailPengadaanBarang.id
            this.detailPengadaanBarang.id_pengadaan_barang = this.selectedDetailPengadaanBarang.id_pengadaan_barang
            this.detailPengadaanBarang.spareparts.kode = this.selectedDetailPengadaanBarang.spareparts.kode
            this.detailPengadaanBarang.spareparts.nama = this.selectedDetailPengadaanBarang.spareparts.nama
            this.detailPengadaanBarang.spareparts.merk = this.selectedDetailPengadaanBarang.spareparts.merk
            this.detailPengadaanBarang.spareparts.tipe = this.selectedDetailPengadaanBarang.spareparts.tipe
            this.detailPengadaanBarang.jumlah_pesan = this.selectedDetailPengadaanBarang.jumlah_pesan
            this.detailPengadaanBarang.jumlah_datang = this.selectedDetailPengadaanBarang.jumlah_datang
            this.detailPengadaanBarang.harga = this.selectedDetailPengadaanBarang.harga
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>