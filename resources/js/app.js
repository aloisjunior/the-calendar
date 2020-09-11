/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');


//****************************************************************
// Autor: Alóis Rothermel
// Data: 26/04/2019
// Obs.: Importação de componentes material design para layout
//       Carregamento de moments
//       Criação de filtro para formatação de data
//****************************************************************
window.Vue = require('vue');

import vuetify from './plugins/vuetify';

window.moment = require('moment');
window.moment.locale('pt-br');

Vue.filter('formatDate', function (value, format = 'DD/MM/YYYY HH:mm') {
    if (value) {
        // console.log(value+' '+format);
        // let formato = (format != null)? format : 'DD/MM/YYYY HH:mm';
        return moment(String(value), ['DD/MM/YYYY HH:mm', "YYYY/MM/DD HH:mm"]).format(format);
    }
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */




// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

window.Vue.prototype.$http = window.axios;
window.Vue.prototype.$my_token = '';

Vue.component('login', require('./components/login').default);
Vue.component('agenda', require('./components/agenda').default);
Vue.component('frontend', require('./components/frontend').default);

//Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    vuetify,
    el: '#app',
});
