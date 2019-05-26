
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import moment from 'moment'
import 'moment/locale/id'
import numeral from 'numeral'

/**
 * Moment
 */

moment.locale('id')

/**
 * Numeral
 */

numeral.register('locale', 'id', {
    delimiters: {
        thousands: '.',
        decimal: ','
    },
    abbreviations: {
        thousand: 'k',
        million: 'm',
        billion: 'b',
        trillion: 't'
    },
    ordinal : function (number) {
        return '';
    },
    currency: {
        symbol: 'Rp'
    }
});

numeral.locale('id');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('admin-kelola-pegawai', require('./components/admin/kelola/pegawai/index.vue').default);
Vue.component('admin-kelola-spareparts', require('./components/admin/kelola/spareparts/index.vue').default);
Vue.component('admin-kelola-supplier', require('./components/admin/kelola/supplier/index.vue').default);
Vue.component('admin-kelola-jasaservice', require('./components/admin/kelola/jasaservice/index.vue').default);
Vue.component('admin-kelola-konsumen', require('./components/admin/kelola/konsumen/index.vue').default);
Vue.component('admin-kelola-kendaraan', require('./components/admin/kelola/kendaraan/index.vue').default);
Vue.component('admin-kelola-cabang', require('./components/admin/kelola/cabang/index.vue').default);

Vue.component('admin-pengadaan-barang', require('./components/admin/pengadaan_barang/index.vue').default);
Vue.component('admin-pengadaan-barang-detail', require('./components/admin/pengadaan_barang/detail.vue').default);

Vue.component('admin-penjualan', require('./components/admin/penjualan/index.vue').default);
Vue.component('admin-penjualan-detail', require('./components/admin/penjualan/detail.vue').default);

Vue.component('admin-pembayaran', require('./components/admin/pembayaran/index.vue').default);
Vue.component('admin-pembayaran-detail', require('./components/admin/pembayaran/detail.vue').default);

Vue.component('admin-laporan-spareparts-terlaris', require('./components/admin/laporan/spareparts_terlaris.vue').default);
Vue.component('admin-laporan-pendapatan-bulanan', require('./components/admin/laporan/pendapatan_bulanan.vue').default);
Vue.component('admin-laporan-pendapatan-tahunan', require('./components/admin/laporan/pendapatan_tahunan.vue').default);
Vue.component('admin-laporan-pengeluaran-bulanan', require('./components/admin/laporan/pengeluaran_bulanan.vue').default);
Vue.component('admin-laporan-penjualan-jasa', require('./components/admin/laporan/penjualan_jasa.vue').default);
Vue.component('admin-laporan-sisa-stok', require('./components/admin/laporan/sisa_stok.vue').default);


Vue.component('siato-index', require('./components/index.vue').default);

/**
 * Filters
 */

Vue.filter('toCurrency', function (value) {
    return numeral(value).format('$0,0')
});

Vue.filter('toNumber', function (value) {
    return numeral(value).format('0,0')
});

Vue.filter('statusPengadaanBarang', function (value) {
    switch(value) {
        case 1:
            return "Terbuka"
        case 2:
            return "Menunggu verifikasi"
        case 3:
            return "Selesai"
    }
});

Vue.filter('statusPenjualan', function (value) {
    switch(value) {
        case 1:
            return "Terbuka"
        case 2:
            return "Menunggu pembayaran"
        case 3:
            return "Selesai"
    }
});

Vue.filter('nomorTransaksiPenjualan', function (value) {
    var _temp = value.split("|")
    return _temp[0] + "-" + moment(String(_temp[1])).format('DDMMYY') + "-" + _temp[2]
});

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    data: {
        app: {
            url: 'http://192.168.100.4:8000/',
        },
        api_key: null,
    },
    created() {
        if(document.querySelector('meta[name="api_key"]')) {
            this.api_key = document.querySelector('meta[name="api_key"]').getAttribute('content')
        }
    },
});
