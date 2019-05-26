<template>
    <div class="modal fade" id="form-tambah-ubah-spareparts" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Data Spareparts</h4>
                    <h4 v-else-if="formAction == 'UBAH'" class="modal-title">Ubah Data Spareparts</h4>
                </div>

                <div class="modal-body">
                    <div v-if="response.error == true" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan</h4>
                        {{ response.message }}
                    </div>
                    <form class="form-horizontal">
                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.kode}">
                            <label class="col-sm-3 control-label">Kode</label>
                            <div class="col-sm-9">
                                <the-mask v-model="spareparts.kode" :disabled="formAction == 'UBAH'" mask="XXXX-XXX-XXX" :masked="true" type="text" class="form-control" placeholder="Kode"></the-mask>
                                <span v-if="response.error && response.data && response.data.kode" class="help-block">{{ response.data.kode[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.nama}">
                            <label class="col-sm-3 control-label">Nama</label>
                            <div class="col-sm-9">
                                <input v-model="spareparts.nama" type="text" class="form-control" placeholder="Nama">
                                <span v-if="response.error && response.data && response.data.nama" class="help-block">{{ response.data.nama[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.merk}">
                            <label class="col-sm-3 control-label">Merk</label>
                            <div class="col-sm-9">
                                <vue-simple-suggest 
                                v-model="spareparts.merk"
                                :list="availableMerk" 
                                :max-suggestions="0"
                                :styles="autoCompleteStyle" 
                                :destyled=true 
                                :filter-by-query="true"
                                placeholder="Merk">
                                </vue-simple-suggest>
                                <span v-if="response.error && response.data && response.data.merk" class="help-block">{{ response.data.merk[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.tipe}">
                            <label class="col-sm-3 control-label">Tipe</label>
                            <div class="col-sm-9">
                                <vue-simple-suggest 
                                v-model="spareparts.tipe"
                                :list="availableTipe" 
                                :max-suggestions="0"
                                :styles="autoCompleteStyle" 
                                :destyled=true 
                                :filter-by-query="true"
                                placeholder="Tipe">
                                </vue-simple-suggest>
                                <span v-if="response.error && response.data && response.data.tipe" class="help-block">{{ response.data.tipe[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.kode_peletakan}">
                            <label class="col-sm-3 control-label">Peletakan</label>
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <select v-model="peletakan.letak" class="form-control">
                                            <option value="null" disabled>Letak</option>
                                            <option value="DPN">Depan</option>
                                            <option value="TGH">Tengah</option>
                                            <option value="BLK">Belakang</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <select v-model="peletakan.ruang" class="form-control">
                                            <option value="null" disabled>Ruang</option>
                                            <option value="KACA">Kaca</option>
                                            <option value="DUS">Dus</option>
                                            <option value="BAN">Ban</option>
                                            <option value="KAYU">Kayu</option>
                                        </select>
                                    </div>

                                    <div class="col-sm-4">
                                        <input v-model="peletakan.nomor" type="number" class="form-control" placeholder="Nomor Urut">
                                    </div>
                                </div>
                                
                                <span v-if="response.error && response.data && response.data.kode_peletakan" class="help-block">{{ response.data.kode_peletakan[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.harga_beli}">
                            <label class="col-sm-3 control-label">Harga Beli</label>
                            <div class="col-sm-9">
                                <money v-model="spareparts.harga_beli" v-bind="money" class="form-control"></money>
                                <span v-if="response.error && response.data && response.data.harga_beli" class="help-block">{{ response.data.harga_beli[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.harga_jual}">
                            <label class="col-sm-3 control-label">Harga Jual</label>
                            <div class="col-sm-9">
                                <money v-model="spareparts.harga_jual" v-bind="money" class="form-control"></money>
                                <span v-if="response.error && response.data && response.data.harga_jual" class="help-block">{{ response.data.harga_jual[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.stok}">
                            <label v-if="formAction == 'TAMBAH'" class="col-sm-3 control-label">Stok Awal</label>
                            <label v-else-if="formAction == 'UBAH'" class="col-sm-3 control-label">Stok</label>
                            <div class="col-sm-9">
                                <input v-model="spareparts.stok" :disabled="formAction == 'UBAH'" type="number" class="form-control" :placeholder="formAction == 'TAMBAH' ? 'Stok Awal' : 'Stok'">
                                <span v-if="response.error && response.data && response.data.stok" class="help-block">{{ response.data.stok[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.stok_minimal}">
                            <label class="col-sm-3 control-label">Stok Minimal</label>
                            <div class="col-sm-9">
                                <input v-model="spareparts.stok_minimal" type="number" class="form-control" placeholder="Stok Minimal">
                                <span v-if="response.error && response.data && response.data.stok_minimal" class="help-block">{{ response.data.stok_minimal[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.gambar}">
                            <label class="col-sm-3 control-label">Gambar</label>
                            <div class="col-sm-9">
                                <input type="file" name="gambar" id="gambar" class="form-control" @change="onImageChange">
                                <span v-if="response.error && response.data && response.data.gambar" class="help-block">{{ response.data.gambar[0] }}</span>
                            </div>
                        </div>
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addSpareparts()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updateSpareparts()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import VueSimpleSuggest from 'vue-simple-suggest'
import {TheMask} from 'vue-the-mask'
import {Money} from 'v-money'

export default {
    components: {VueSimpleSuggest, TheMask, Money},
    props: ['formAction', 'selectedSpareparts'],
    data: function() {
        return {
            spareparts: {
                kode: null,
                nama: null,
                merk: null,
                tipe: null,
                harga_beli: 0,
                harga_jual: 0,
                stok: null,
                stok_minimal: null,
                url_gambar: null
            },
            peletakan: {
                letak: null,
                ruang: null,
                nomor: null
            },
            gambar: null,
            availableMerk: null,
            availableTipe: null,
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
            },
            autoCompleteStyle: {
                vueSimpleSuggest: "vue-simple-suggest designed",
                defaultInput: "form-control"
            },
        }
    },
    methods: {
        getAvailableMerkAndTipe() {
            axios.get(this.$root.app.url + 'api/data/spareparts/index/merk')
            .then(response => {
                this.availableMerk = response.data.data
            })

            axios.get(this.$root.app.url + 'api/data/spareparts/index/tipe')
            .then(response => {
                this.availableTipe = response.data.data
            })
        },
        addSpareparts() {
            axios.post(this.$root.app.url + 'api/data/spareparts/', {
                kode: this.spareparts.kode,
                nama: this.spareparts.nama,
                merk: this.spareparts.merk,
                tipe: this.spareparts.tipe,
                kode_peletakan: this.peletakan.letak + "-" + this.peletakan.ruang + "-" + this.peletakan.nomor,
                harga_beli: this.spareparts.harga_beli,
                harga_jual: this.spareparts.harga_jual,
                stok: this.spareparts.stok,
                stok_minimal: this.spareparts.stok_minimal,
                gambar: this.gambar,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reloadList = true
                    $('#form-tambah-ubah-spareparts').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        updateSpareparts() {
            axios.put(this.$root.app.url + 'api/data/spareparts/' + this.spareparts.kode, {
                nama: this.spareparts.nama,
                merk: this.spareparts.merk,
                tipe: this.spareparts.tipe,
                kode_peletakan: this.peletakan.letak + "-" + this.peletakan.ruang + "-" + this.peletakan.nomor,
                harga_beli: this.spareparts.harga_beli,
                harga_jual: this.spareparts.harga_jual,
                stok_minimal: this.spareparts.stok_minimal,
                gambar: this.gambar,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reloadList = true
                    $('#form-tambah-ubah-spareparts').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        close() {
            this.spareparts.kode = null
            this.spareparts.nama = null
            this.spareparts.merk = null
            this.spareparts.tipe = null
            this.spareparts.harga_beli = null
            this.spareparts.harga_jual = null
            this.spareparts.stok = null
            this.spareparts.stok_minimal = null
            this.spareparts.url_gambar = null

            this.peletakan.letak = null
            this.peletakan.ruang = null
            this.peletakan.nomor = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
        onImageChange(e) {
            let files = e.target.files || e.dataTransfer.files;
            let vm = this;
            if (!files.length) {
                vm.gambar = null;
                return;
            }

            this.createImage(files[0]);
        },
        createImage(file) {
            let reader = new FileReader();
            let vm = this;
            reader.onload = (e) => {
                vm.gambar = e.target.result;
            };
            reader.readAsDataURL(file);
        },
    },
    created() {
        this.getAvailableMerkAndTipe()
        if(this.selectedSpareparts != null) {
            this.spareparts.kode = this.selectedSpareparts.kode
            this.spareparts.nama = this.selectedSpareparts.nama
            this.spareparts.merk = this.selectedSpareparts.merk
            this.spareparts.tipe = this.selectedSpareparts.tipe
            this.spareparts.harga_beli = this.selectedSpareparts.harga_beli
            this.spareparts.harga_jual = this.selectedSpareparts.harga_jual
            this.spareparts.stok = this.selectedSpareparts.stok
            this.spareparts.stok_minimal = this.selectedSpareparts.stok_minimal
            this.spareparts.url_gambar = this.selectedSpareparts.url_gambar

            var kode_peletakan = this.selectedSpareparts.kode_peletakan.split('-')
            this.peletakan.letak = kode_peletakan[0]
            this.peletakan.ruang = kode_peletakan[1]
            this.peletakan.nomor = kode_peletakan[2]
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>

<style>
.vue-simple-suggest > ul {
  list-style: none;
  margin: 0;
  padding: 0;
}

.vue-simple-suggest.designed {
  position: relative;
}

.vue-simple-suggest.designed, .vue-simple-suggest.designed * {
  -webkit-box-sizing: border-box;
          box-sizing: border-box;
}

.vue-simple-suggest.designed .suggestions {
  position: absolute;
  left: 0;
  right: 0;
  top: 100%;
  top: calc(100% + 5px);
  border-radius: 3px;
  border: 1px solid #aaa;
  background-color: #fff;
  opacity: 1;
  z-index: 1000;
}

.vue-simple-suggest.designed .suggestions .suggest-item {
  cursor: pointer;
  -webkit-user-select: none;
     -moz-user-select: none;
      -ms-user-select: none;
          user-select: none;
}

.vue-simple-suggest.designed .suggestions .suggest-item,
.vue-simple-suggest.designed .suggestions .misc-item {
  padding: 5px 10px;
}

.vue-simple-suggest.designed .suggestions .suggest-item.hover {
  background-color: #2874D5 !important;
  color: #fff !important;
}

.vue-simple-suggest.designed .suggestions .suggest-item.selected {
  background-color: #2832D5;
  color: #fff;
}
</style>