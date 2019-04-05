<template>
    <div class="modal fade" id="form-tambah-ubah" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Data Cabang</h4>
                    <h4 v-else-if="formAction == 'UBAH'" class="modal-title">Ubah Data Cabang</h4>
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
                                <input v-model="cabang.nama" type="text" class="form-control" placeholder="Nama">
                                <span v-if="response.error && response.data && response.data.nama" class="help-block">{{ response.data.nama[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.merk}">
                            <label class="col-sm-3 control-label">Nomor telepon</label>
                            <div class="col-sm-9">
                                <input v-model="cabang.nomor_telepon" type="text" class="form-control" placeholder="Nomor telepon">
                                <span v-if="response.error && response.data && response.data.merk" class="help-block">{{ response.data.merk[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.tipe}">
                            <label class="col-sm-3 control-label">Alamat</label>
                            <div class="col-sm-9">
                                <textarea v-model="cabang.alamat" type="text" class="form-control" rows="3" placeholder="Alamat"></textarea>
                                <span v-if="response.error && response.data && response.data.tipe" class="help-block">{{ response.data.tipe[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addCabang()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updateCabang()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['formAction', 'selectedCabang'],
    data: function() {
        return {
            cabang: {
                id: null,
                nama: null,
                nomor_telepon: null,
                alamat: null,
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
        addCabang() {
            axios.post(this.$root.app.url + 'api/data/cabang/', {
                nama: this.cabang.nama,
                nomor_telepon: this.cabang.nomor_telepon,
                alamat: this.cabang.alamat,
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
        updateCabang() {
            axios.put(this.$root.app.url + 'api/data/cabang/' + this.cabang.id, {
                nama: this.cabang.nama,
                nomor_telepon: this.cabang.nomor_telepon,
                alamat: this.cabang.alamat,
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
            this.cabang.id = null
            this.cabang.nama = null
            this.cabang.nomor_telepon = null
            this.cabang.alamat = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        if(this.selectedCabang != null) {
            this.cabang.id = this.selectedCabang.id
            this.cabang.nama = this.selectedCabang.nama
            this.cabang.nomor_telepon = this.selectedCabang.nomor_telepon
            this.cabang.alamat = this.selectedCabang.alamat
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>