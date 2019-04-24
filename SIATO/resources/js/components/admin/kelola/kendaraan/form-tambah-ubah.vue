<template>
    <div class="modal fade" id="form-tambah-ubah" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Data Kendaraan</h4>
                    <h4 v-else-if="formAction == 'UBAH'" class="modal-title">Ubah Data Kendaraan</h4>
                </div>

                <div class="modal-body">
                    <div v-if="response.error == true" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan</h4>
                        {{ response.message }}
                    </div>
                    <form class="form-horizontal">
                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.nomor_polisi}">
                            <label class="col-sm-3 control-label">Nomor Polisi</label>
                            <div class="col-sm-9">
                                <input v-model="kendaraan.nomor_polisi" type="text" class="form-control" placeholder="Nomor Polisi">
                                <span v-if="response.error && response.data && response.data.nomor_polisi" class="help-block">{{ response.data.nomor_polisi[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.merk}">
                            <label class="col-sm-3 control-label">Merk</label>
                            <div class="col-sm-9">
                                <input v-model="kendaraan.merk" type="text" class="form-control" placeholder="Merk">
                                <span v-if="response.error && response.data && response.data.merk" class="help-block">{{ response.data.merk[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.tipe}">
                            <label class="col-sm-3 control-label">Tipe</label>
                            <div class="col-sm-9">
                                <input v-model="kendaraan.tipe" type="text" class="form-control" placeholder="Tipe">
                                <span v-if="response.error && response.data && response.data.tipe" class="help-block">{{ response.data.tipe[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.id_pemilik}">
                            <label for="pemilik" class="col-sm-3 control-label">Pemilik</label>
                            <div class="col-sm-9">
                                <select v-model="kendaraan.pemilik.id" class="form-control" id="pemilik">
                                    <option v-for="(konsumen, index) in listKonsumen" v-bind:key="index" v-bind:value="konsumen.id" >{{ konsumen.nama }}</option>
                                </select>
                                <span v-if="response.error && response.data && response.data.id_pemilik" class="help-block">{{ response.data.id_pemilik[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addKendaraaan()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updatekendaraan()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import formTambahUbah from '../konsumen/form-tambah-ubah.vue';
export default {
    components: {
      formTambahUbah,
    },
    props: ['formAction', 'selectedKendaraan'],
    data: function() {
        return {
            kendaraan: {
                nomor_polisi: null,
                merk: null,
                tipe: null,
                pemilik: {
                    id: null,
                    nama: null,
                    nomor_telepon: null,
                    alamat: null
                },
            },
            response: {
                error: false,
                message: '',
                data: null
            },
            reloadList: false,
            listKonsumen: null,
        }
    },
    methods: {
        getAllKonsumen() {
            axios.post(this.$root.app.url + 'api/data/konsumen/index', {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listKonsumen = response.data.data

                    if($.fn.dataTable.isDataTable( '#mytable')) {
                        $('#mytable').DataTable().destroy()
                    }
                }
            })
        },
        addKendaraaan() {
            axios.post(this.$root.app.url + 'api/data/kendaraan/', {
                nomor_polisi: this.kendaraan.nomor_polisi,
                merk: this.kendaraan.merk,
                tipe: this.kendaraan.tipe,
                id_pemilik: this.kendaraan.pemilik.id,
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
        updatekendaraan() {
            axios.put(this.$root.app.url + 'api/data/kendaraan/' + this.kendaraan.id, {
                merk: this.kendaraan.merk,
                tipe: this.kendaraan.tipe,
                id_pemilik: this.kendaraan.pemilik.id,
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
            this.kendaraan.id = null
            this.kendaraan.nomor_polisi = null
            this.kendaraan.merk = null
            this.kendaraan.tipe = null
            this.kendaraan.pemilik.id = null
            this.kendaraan.pemilik.nama = null
            this.kendaraan.pemilik.nomor_telepon = null
            this.kendaraan.pemilik.alamat = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        this.getAllKonsumen()
        if(this.selectedKendaraan != null) {
            this.kendaraan.nomor_polisi = this.selectedkendaraan.nomor_polisi
            this.kendaraan.merk = this.selectedkendaraan.merk
            this.kendaraan.tipe = this.selectedkendaraan.tipe
            this.kendaraan.pemilik.id = this.selectedkendaraan.pemilik.id
            this.kendaraan.pemilik.nama = this.selectedkendaraan.pemilik.nama
            this.kendaraan.pemilik.nomor_telepon = this.selectedkendaraan.pemilik.nomor_telepon
            this.kendaraan.pemilik.alamat = this.selectedkendaraan.pemilik.alamat
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>