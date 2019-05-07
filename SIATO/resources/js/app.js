
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

import numeral from 'numeral'

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

Vue.component('konsumen', require('./components/konsumen/index.vue').default);

Vue.component('admin-kelola-pegawai', require('./components/admin/kelola/pegawai/index.vue').default);
Vue.component('admin-kelola-spareparts', require('./components/admin/kelola/spareparts/index.vue').default);
Vue.component('admin-kelola-konsumen', require('./components/admin/kelola/konsumen/index.vue').default);
Vue.component('admin-kelola-cabang', require('./components/admin/kelola/cabang/index.vue').default);
Vue.component('admin-kelola-kendaraan', require('./components/admin/kelola/kendaraan/index.vue').default);
Vue.component('admin-kelola-supplier', require('./components/admin/kelola/supplier/index.vue').default);
Vue.component('admin-kelola-jasaservice', require('./components/admin/kelola/jasaservice/index.vue').default);

Vue.component('admin-pengadaan-barang', require('./components/admin/pengadaan_barang/index.vue').default);
Vue.component('admin-pengadaan-barang-detail', require('./components/admin/pengadaan_barang/detail.vue').default);

Vue.component('admin-penjualan', require('./components/admin/penjualan/index.vue').default);
Vue.component('admin-penjualan-detail', require('./components/admin/penjualan/detail.vue').default);


/**
 * Filters
 */

Vue.filter('toCurrency', function (value) {
    return numeral(value).format('$0,0.00')
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
            url: 'http://127.0.0.1:8000/',
        },
        api_key: null,
    },
    created() {
        if(document.querySelector('meta[name="api_key"]')) {
            this.api_key = document.querySelector('meta[name="api_key"]').getAttribute('content')
        }
    },
});
