<template>
    <div class="modal fade" id="form-tambah-ubah-pegawai" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Data Pegawai</h4>
                    <h4 v-else-if="formAction == 'UBAH'" class="modal-title">Ubah Data Pegawai</h4>
                </div>

                <div class="modal-body">
                    <div v-if="response.error == true" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan</h4>
                        {{ response.message }}
                    </div>
                    <form class="form-horizontal">
                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.username}">
                            <label class="col-sm-3 control-label">Nama pengguna</label>
                            <div class="col-sm-9">
                                <input v-model="pegawai.username" :disabled="formAction == 'UBAH'" type="text" class="form-control" placeholder="Nama pengguna">
                                <span v-if="response.error && response.data && response.data.username" class="help-block">{{ response.data.username[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.password}">
                            <label class="col-sm-3 control-label">Kata sandi</label>
                            <div class="col-sm-9">
                                <input v-model="pegawai.password" type="password" class="form-control" placeholder="Kata sandi">
                                <span v-if="response.error && response.data && response.data.password" class="help-block">{{ response.data.password[0] }}</span>
                            </div>
                        </div>

                        <hr>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.nama}">
                            <label class="col-sm-3 control-label">Nama</label>
                            <div class="col-sm-9">
                                <input v-model="pegawai.nama" type="text" class="form-control" placeholder="Nama">
                                <span v-if="response.error && response.data && response.data.nama" class="help-block">{{ response.data.nama[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.nomor_telepon}">
                            <label class="col-sm-3 control-label">Nomor telepon</label>
                            <div class="col-sm-9">
                                <the-mask v-model="pegawai.nomor_telepon" :mask="['#### #### ####', '##### #### ####']" type="text" class="form-control" placeholder="Nomor telepon"></the-mask>
                                <span v-if="response.error && response.data && response.data.nomor_telepon" class="help-block">{{ response.data.nomor_telepon[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.alamat}">
                            <label class="col-sm-3 control-label">Alamat</label>
                            <div class="col-sm-9">
                                <textarea v-model="pegawai.alamat" class="form-control" rows="3" placeholder="Alamat"></textarea>
                                <span v-if="response.error && response.data && response.data.alamat" class="help-block">{{ response.data.alamat[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.gaji}">
                            <label class="col-sm-3 control-label">Gaji</label>
                            <div class="col-sm-9">
                                <money v-model="pegawai.gaji" v-bind="money" class="form-control"></money>
                                <span v-if="response.error && response.data && response.data.gaji" class="help-block">{{ response.data.gaji[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.role}">
                            <label class="col-sm-3 control-label">Peran</label>
                            <div class="col-sm-9">
                                <select v-model="pegawai.role" class="form-control">
                                    <option value="null" disabled>Pilih peran</option>
                                    <option value="1">CS</option>
                                    <option value="2">Kasir</option>
                                    <option value="3">Montir</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.role" class="help-block">{{ response.data.role[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addPegawai()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updatePegawai()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {TheMask} from 'vue-the-mask'
import {Money} from 'v-money'

export default {
    components: {TheMask, Money},
    props: ['formAction', 'selectedPegawai'],
    data: function() {
        return {
            pegawai: {
                id: null,
                username: null,
                password: null,
                nama: null,
                nomor_telepon: null,
                alamat: null,
                gaji: 0,
                role: null
            },
            response: {
                error: false,
                message: '',
                data: null
            },
            reloadList: false,
            money: {
                precision: 0,
                decimal: ',',
                thousands: '.',
                prefix: 'Rp '
            }
        }
    },
    methods: {
        addPegawai() {
            axios.post(this.$root.app.url + 'api/data/pegawai/', {
                username: this.pegawai.username,
                password: this.pegawai.password,
                nama: this.pegawai.nama,
                nomor_telepon: this.pegawai.nomor_telepon,
                alamat: this.pegawai.alamat,
                gaji: this.pegawai.gaji,
                role: this.pegawai.role,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reloadList = true
                    $('#form-tambah-ubah-pegawai').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        updatePegawai() {
            axios.put(this.$root.app.url + 'api/data/pegawai/' + this.pegawai.id, {
                password: this.pegawai.password,
                nama: this.pegawai.nama,
                nomor_telepon: this.pegawai.nomor_telepon,
                alamat: this.pegawai.alamat,
                gaji: this.pegawai.gaji,
                role: this.pegawai.role,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reloadList = true
                    $('#form-tambah-ubah-pegawai').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        close() {
            this.pegawai.id = null
            this.pegawai.username = null
            this.pegawai.password = null
            this.pegawai.nama = null
            this.pegawai.nomor_telepon = null
            this.pegawai.alamat = null
            this.pegawai.gaji = null
            this.pegawai.role = null

            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        }
    },
    created() {
        if(this.selectedPegawai != null) {
            this.pegawai.id = this.selectedPegawai.id
            this.pegawai.username = this.selectedPegawai.username
            this.pegawai.nama = this.selectedPegawai.nama
            this.pegawai.nomor_telepon = this.selectedPegawai.nomor_telepon
            this.pegawai.alamat = this.selectedPegawai.alamat
            this.pegawai.gaji = this.selectedPegawai.gaji
            this.pegawai.role = this.selectedPegawai.role
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>