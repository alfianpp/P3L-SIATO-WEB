<template>
    <div class="modal fade" id="form-verifikasi-pengadaan-barang" ref="modal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Verifikasi Barang Datang</h4>
                </div>

                <div class="modal-body">
                    <div v-if="response.error == true" class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h4><i class="icon fa fa-ban"></i> Terjadi Kesalahan</h4>
                        {{ response.message }}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Merk</th>
                                    <th>Jumlah Pesan</th>
                                    <th>Jumlah Datang</th>
                                    <th>Harga Satuan</th>
                                </tr>
                            </thead>

                            <tbody>
                                <tr v-for="(detail, index) in listDetailPengadaanBarang" v-bind:key="index">
                                    <td>{{ detail.spareparts.kode }}</td>
                                    <td>{{ detail.spareparts.nama }}</td>
                                    <td>{{ detail.spareparts.merk }}</td>
                                    <td>{{ detail.jumlah_pesan }}</td>
                                    <td><input v-model="listDetailPengadaanBarang[index].jumlah_datang" type="number" style="width:100%; padding:0px 5px" placeholder="Jumlah datang"></td>
                                    <td><money v-model="listDetailPengadaanBarang[index].harga" v-bind="money" style="width:100%; padding:0px 5px"></money></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                    <button @click="verifikasiPengadaanBarang()" type="button" class="btn btn-success">Verifikasi</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {Money} from 'v-money'

export default {
    components: {Money},
    props: ['idPengadaanBarang'],
    data: function() {
        return {
            listDetailPengadaanBarang: null,
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
        getDetailPengadaanBarang() {
            axios.post(this.$root.app.url + 'api/transaksi/pengadaan/detail/' + this.idPengadaanBarang, {
                api_key: this.$root.api_key,
            })
            .then(response => {
                if(response.data.error == false) {
                    this.listDetailPengadaanBarang = response.data.data
                }
            })
        },
        verifikasiPengadaanBarang() {
            var _temp = []
            this.listDetailPengadaanBarang.forEach(detailPengadaanBarang => {
                var detail = {
                    id: detailPengadaanBarang.id,
                    jumlah_datang: detailPengadaanBarang.jumlah_datang,
                    harga: detailPengadaanBarang.harga
                }
                _temp.push(detail)
            });
            
            axios.post(this.$root.app.url + 'api/transaksi/pengadaan/verifikasi', {
                id_pengadaan_barang: this.idPengadaanBarang,
                detail_pengadaan_barang: _temp,
                api_key: this.$root.api_key,
            })
            .then(response => {
                this.response = response.data
                if(this.response.error == false) {
                    alert(this.response.message)
                    this.reloadList = true
                    $('#form-verifikasi-pengadaan-barang').modal('hide');
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                }
            })
            
        },
        close() {
            this.listDetailPengadaanBarang = null
            
            this.response.error = false
            this.response.message = ''
            this.response.data = null

            this.$emit('close', this.reloadList)
            this.reloadList = false
        },
    },
    created() {
        this.getDetailPengadaanBarang()
    },
    mounted() {
        $(this.$refs.modal).on("hidden.bs.modal", this.close)
    }
}
</script>