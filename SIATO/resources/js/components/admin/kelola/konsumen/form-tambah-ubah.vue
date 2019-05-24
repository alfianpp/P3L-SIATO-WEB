<template>
    <div class="modal fade" id="form-tambah-ubah-konsumen" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Data Konsumen</h4>
                    <h4 v-else-if="formAction == 'UBAH'" class="modal-title">Ubah Data Konsumen</h4>
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
                                <input v-model="konsumen.nama" type="text" class="form-control" placeholder="Nama">
                                <span v-if="response.error && response.data && response.data.nama" class="help-block">{{ response.data.nama[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.nomor_telepon}">
                            <label class="col-sm-3 control-label">Nomor telepon</label>
                            <div class="col-sm-9">
                                <the-mask v-model="konsumen.nomor_telepon" :mask="['#### #### ####', '##### #### ####']" type="text" class="form-control" placeholder="Nomor telepon"></the-mask>
                                <span v-if="response.error && response.data && response.data.nomor_telepon" class="help-block">{{ response.data.nomor_telepon[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.alamat}">
                            <label class="col-sm-3 control-label">Alamat</label>
                            <div class="col-sm-9">
                                <textarea v-model="konsumen.alamat" type="text" class="form-control" rows="3" placeholder="Alamat"></textarea>
                                <span v-if="response.error && response.data && response.data.alamat" class="help-block">{{ response.data.alamat[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addKonsumen()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updateKonsumen()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {TheMask} from 'vue-the-mask'

export default {
    components: {TheMask},
    props: ['formAction', 'selectedKonsumen'],
    data: function() {
        return {
            konsumen: {
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
        addKonsumen() {
            axios.post(this.$root.app.url + 'api/data/konsumen/', {
                nama: this.konsumen.nama,
                nomor_telepon: this.konsumen.nomor_telepon,
                alamat: this.konsumen.alamat,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reloadList = true
                    $('#form-tambah-ubah-konsumen').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        updateKonsumen() {
            axios.put(this.$root.app.url + 'api/data/konsumen/' + this.konsumen.id, {
                nama: this.konsumen.nama,
                nomor_telepon: this.konsumen.nomor_telepon,
                alamat: this.konsumen.alamat,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reloadList = true
                    $('#form-tambah-ubah-konsumen').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        close() {
            this.konsumen.id = null
            this.konsumen.nama = null
            this.konsumen.nomor_telepon = null
            this.konsumen.alamat = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        if(this.selectedKonsumen != null) {
            this.konsumen.id = this.selectedKonsumen.id
            this.konsumen.nama = this.selectedKonsumen.nama
            this.konsumen.nomor_telepon = this.selectedKonsumen.nomor_telepon
            this.konsumen.alamat = this.selectedKonsumen.alamat
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>