<template>
    <div class="modal fade" id="form-tambah-ubah" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Data Supplier</h4>
                    <h4 v-else-if="formAction == 'UBAH'" class="modal-title">Ubah Data Supplier</h4>
                </div>

                <div class="modal-body">
                    <div v-if="response.error == true" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan</h4>
                        {{ response.message }}
                    </div>
                    <form class="form-horizontal">
                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.nama}">
                            <label class="col-sm-3 control-label">Nama</label>
                            <div class="col-sm-9">
                                <input v-model="supplier.nama" type="text" class="form-control" placeholder="Nama">
                                <span v-if="response.error && response.data && response.data.nama" class="help-block">{{ response.data.nama[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.alamat}">
                            <label class="col-sm-3 control-label">Alamat</label>
                            <div class="col-sm-9">
                                <input v-model="supplier.alamat" type="text" class="form-control" placeholder="Alamat">
                                <span v-if="response.error && response.data && response.data.alamat" class="help-block">{{ response.data.alamat[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.nama_sales}">
                            <label class="col-sm-3 control-label">Nama Sales</label>
                            <div class="col-sm-9">
                                <textarea v-model="supplier.nama_sales" type="text" class="form-control" rows="3" placeholder="Nama Sales"></textarea>
                                <span v-if="response.error && response.data && response.data.nama_sales" class="help-block">{{ response.data.nama_sales[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.nomor_telepon_sales}">
                            <label class="col-sm-3 control-label">Nomor Telepon Sales</label>
                            <div class="col-sm-9">
                                <textarea v-model="supplier.nomor_telepon_sales" type="text" class="form-control" rows="3" placeholder="Nomor Telepon Sales"></textarea>
                                <span v-if="response.error && response.data && response.data.nomor_telepon_sales" class="help-block">{{ response.data.nomor_telepon_sales[0] }}</span>
                            </div>
                        </div>

                        
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addSupplier()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updateSupplier()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['formAction', 'selectedSupplier'],
    data: function() {
        return {
            supplier: {
                id: null,
                nama: null,
                alamat: null,
                nama_sales: null,
                nomor_telepon_sales: null,
            },
            response: {
                error: false,
                message: '',
                data: null
            },
            reloadList: false,
        }
    },
    methods: {
        addSupplier() {
            axios.post(this.$root.app.url + 'api/data/supplier/', {
                nama: this.supplier.nama,
                alamat: this.supplier.alamat,
                nama_sales: this.supplier.nama_sales,
                nomor_telepon_sales: this.supplier. nomor_telepon_sales,
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
        updateSupplier() {
            axios.put(this.$root.app.url + 'api/data/supplier/' + this.supplier.id, {
                nama: this.supplier.nama,
                alamat: this.supplier.alamat,
                nama_sales: this.supplier.nama_sales,
                nomor_telepon_sales: this.supplier. nomor_telepon_sales,
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
            this.supplier.id = null
            this.supplier.nama = null
            this.supplier.alamat = null
            this.supplier.nama_sales = null
            this.supplier.nomor_telepon_sales = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        if(this.selectedSupplier != null) {
            this.supplier.id = this.selectedSupplier.id
            this.supplier.nama = this.selectedSupplier.nama
            this.supplier.alamat = this.selectedSupplier.alamat
            this.supplier.nama_sales = this.selectedSupplier.nama_sales
            this.supplier.nomor_telepon_sales = this.selectedSupplier.nomor_telepon_sales
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>