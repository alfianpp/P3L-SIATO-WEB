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
                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.id_supplier}">
                            <label class="col-sm-3 control-label">Supplier</label>
                            <div class="col-sm-9">
                                <select v-model="pengadaan_barang.supplier.id" class="form-control">
                                    <option value="null" disabled>Pilih supplier</option>
                                    <option v-for="(supplier, index) in listSupplier" v-bind:key="index" v-bind:value="supplier.id">{{ supplier.nama }}</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.id_supplier" class="help-block">{{ response.data.id_supplier[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addPengadaanBarang()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updatePengadaanBarang()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['formAction', 'selectedPengadaanBarang'],
    data: function() {
        return {
            pengadaan_barang: {
                id: null,
                supplier: {
                    id: null,
                    nama: null,
                },
                total: null,
                status: null,
                tgl_transaksi: null
            },
            listSupplier: null,
            response: {
                error: false,
                message: '',
                data: null
            },
            reloadList: false,
        }
    },
    methods: {
        addPengadaanBarang() {
            axios.post(this.$root.app.url + 'api/transaksi/pengadaan/data', {
                id_supplier: this.pengadaan_barang.supplier.id,
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
        updatePengadaanBarang() {
            axios.put(this.$root.app.url + 'api/transaksi/pengadaan/data/' + this.pengadaan_barang.id, {
                id_supplier: this.pengadaan_barang.supplier.id,
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
        getSupplier() {
            axios.post(this.$root.app.url + 'api/data/supplier/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    this.listSupplier = this.response.data
                }
            })
        },
        close() {
            this.pengadaan_barang.id = null
            this.pengadaan_barang.supplier.id = null
            this.pengadaan_barang.supplier.nama = null
            this.pengadaan_barang.total = null
            this.pengadaan_barang.status = null
            this.pengadaan_barang.tgl_transaksi = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        this.getSupplier()
        if(this.selectedPengadaanBarang != null) {
            this.pengadaan_barang.id = this.selectedPengadaanBarang.id
            this.pengadaan_barang.supplier.id = this.selectedPengadaanBarang.supplier.id
            this.pengadaan_barang.supplier.nama = this.selectedPengadaanBarang.supplier.nama
            this.pengadaan_barang.total = this.selectedPengadaanBarang.total
            this.pengadaan_barang.status = this.selectedPengadaanBarang.status
            this.pengadaan_barang.tgl_transaksi = this.selectedPengadaanBarang.tgl_transaksi
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>