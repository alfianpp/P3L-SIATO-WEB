<template>
    <div>
        <div class="modal fade" id="form-tambah-ubah-kendaraan" ref="modal">
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
                                    <input v-model="kendaraan.nomor_polisi" :disabled="formAction == 'UBAH'" type="text" class="form-control" placeholder="Nomor polisi">
                                    <span v-if="response.error && response.data && response.data.nomor_polisi" class="help-block">{{ response.data.nomor_polisi[0] }}</span>
                                </div>
                            </div>

                            <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.merk}">
                                <label class="col-sm-3 control-label">Merk</label>
                                <div class="col-sm-9">
                                    <vue-simple-suggest 
                                    v-model="kendaraan.merk"
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
                                    v-model="kendaraan.tipe"
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

                            <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.id_pemilik}">
                                <label for="pemilik" class="col-sm-3 control-label">Pemilik</label>
                                <div class="col-sm-9">
                                    <vue-simple-suggest 
                                    v-model="kendaraan.pemilik.nama" 
                                    :list="listKonsumen" 
                                    :max-suggestions="0" 
                                    :min-length="3" 
                                    display-attribute="nama" 
                                    :styles="autoCompleteStyle" 
                                    :destyled=true 
                                    :filter-by-query="false" 
                                    placeholder="Nama pemilik" 
                                    @input="searchKonsumen"
                                    @select="onSuggestSelect"
                                    @blur="onBlur">
                                    </vue-simple-suggest>
                                    <a href="#" @click="openForm()" data-toggle="modal" data-target="#form-tambah-ubah-konsumen"><small>Tambah Konsumen</small></a>
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

        <form-tambah-ubah 
        v-if="showForm == true" 
        v-bind:form-action="'TAMBAH'" 
        v-bind:selected-konsumen="null" 
        v-on:close="closeForm">
        </form-tambah-ubah>
    </div>
</template>

<script>
import VueSimpleSuggest from 'vue-simple-suggest'

import formTambahUbah from '../konsumen/form-tambah-ubah.vue';

export default {
    components: {VueSimpleSuggest, formTambahUbah},
    props: ['formAction', 'selectedKendaraan'],
    data: function() {
        return {
            kendaraan: {
                nomor_polisi: null,
                merk: null,
                tipe: null,
                pemilik: {
                    id: null,
                    nama: null
                }
            },
            listKonsumen: [],
            availableMerk: null,
            availableTipe: null,
            response: {
                error: false,
                message: '',
                data: null
            },
            reloadList: false,
            showForm: false,
            selected: null,
            autoCompleteStyle: {
                vueSimpleSuggest: "vue-simple-suggest designed",
                defaultInput: "form-control"
            },
        }
    },
    methods: {
        getAvailableMerkAndTipe() {
            axios.get(this.$root.app.url + 'api/data/kendaraan/index/merk')
            .then(response => {
                this.availableMerk = response.data.data
            })

            axios.get(this.$root.app.url + 'api/data/kendaraan/index/tipe')
            .then(response => {
                this.availableTipe = response.data.data
            })
        },
        searchKonsumen() {
            axios.get(this.$root.app.url + 'api/data/konsumen/index/search', {
                params: {
                    nama: this.kendaraan.pemilik.nama
                }
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    this.listKonsumen = this.response.data
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
                    $('#form-tambah-ubah-kendaraan').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        updatekendaraan() {
            axios.put(this.$root.app.url + 'api/data/kendaraan/' + this.kendaraan.nomor_polisi, {
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
                    $('#form-tambah-ubah-kendaraan').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
        },
        openForm() {
            this.showForm = true
        },
        closeForm(reloadList) {
            this.showForm = false
        },
        onSuggestSelect(konsumen) {
            this.selected = konsumen
            this.kendaraan.pemilik.id = konsumen.id
            this.kendaraan.pemilik.nama = konsumen.nama
        },
        onBlur() {
            this.kendaraan.pemilik.id = this.selected.id
            this.kendaraan.pemilik.nama = this.selected.nama
        },
        close() {
            this.kendaraan.nomor_polisi = null
            this.kendaraan.merk = null
            this.kendaraan.tipe = null
            this.kendaraan.pemilik.id = null
            this.kendaraan.pemilik.nama = null

            this.listKonsumen = []
            this.availableMerk = null
            this.availableTipe = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
            
            this.selected = null
        },
    },
    created() {
        this.getAvailableMerkAndTipe()
        if(this.selectedKendaraan != null) {
            this.kendaraan.nomor_polisi = this.selectedKendaraan.nomor_polisi
            this.kendaraan.merk = this.selectedKendaraan.merk
            this.kendaraan.tipe = this.selectedKendaraan.tipe
            this.kendaraan.pemilik = this.selectedKendaraan.pemilik
            this.listKonsumen.push(this.selectedKendaraan.pemilik)
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>

<style scoped>
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