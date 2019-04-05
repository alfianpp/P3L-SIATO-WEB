
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

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
Vue.component('admin-kelola-cabang', require('./components/admin/kelola/cabang/index.vue').default);

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
