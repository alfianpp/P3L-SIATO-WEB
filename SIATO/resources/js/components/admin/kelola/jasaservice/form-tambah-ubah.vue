<template>
    <div class="modal fade" id="form-tambah-ubah" ref="modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 v-if="formAction == 'TAMBAH'" class="modal-title">Tambah Data Jasa Service</h4>
                    <h4 v-else-if="formAction == 'UBAH'" class="modal-title">Ubah Data Jasa Service</h4>
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
                                <input v-model="jasaservice.nama" type="text" class="form-control" placeholder="Nama">
                                <span v-if="response.error && response.data && response.data.nama" class="help-block">{{ response.data.nama[0] }}</span>
                            </div>
                        </div>

                        <div class="form-group" v-bind:class="{'has-error': response.error && response.data && response.data.harga_jual}">
                            <label class="col-sm-3 control-label">Harga Jual</label>
                            <div class="col-sm-9">
                                <input v-model="jasaservice.harga_jual" type="text" class="form-control" placeholder="Harga Jual">
                                <span v-if="response.error && response.data && response.data.harga_jual" class="help-block">{{ response.data.harga_jual[0] }}</span>
                            </div>
                        </div>

                        
                    </form>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button v-if="formAction == 'TAMBAH'" @click="addJasa Service()" type="button" class="btn btn-success">Tambah</button>
                    <button v-if="formAction == 'UBAH'" @click="updateJasa Service()" type="button" class="btn btn-primary">Ubah</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['formAction', 'selectedJasa Service'],
    data: function() {
        return {
            jasaservice: {
                id: null,
                nama: null,
                harga_jual: null,
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
        addJasa Service() {
            axios.post(this.$root.app.url + 'api/data/jasaservice/', {
                nama: this.jasaservice.nama,
                harga_jual: this.jasaservice.harga_jual,
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
        updateJasa Service() {
            axios.put(this.$root.app.url + 'api/data/jasaservice/' + this.jasaservice.id, {
                nama: this.jasaservice.nama,
                harga_jual: this.jasaservice.harga_jual,
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
            this.jasaservice.id = null
            this.jasaservice.nama = null
            this.jasaservice.harga_jual = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        if(this.selectedJasa Service != null) {
            this.jasaservice.id = this.selectedJasa Service.id
            this.jasaservice.nama = this.selectedJasa Service.nama
            this.jasaservice.harga_jual = this.selectedJasa Service.harga_jual
        }
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>