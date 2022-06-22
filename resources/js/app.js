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

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))
//global registration
import { func, variables } from './components/global.js';

Vue.prototype.$func = func
Vue.prototype.$variables = variables

// FormWizard
import VueFormWizard from 'vue-form-wizard'
import 'vue-form-wizard/dist/vue-form-wizard.min.css'
Vue.use(VueFormWizard)

// Vuetify
// import Vuetify from 'vuetify'
// Vue.use(Vuetify)

// VeeValidate
// import VeeValidate from 'vee-validate'
// Vue.use(VeeValidate);

// Vuelidate
import Vuelidate from 'vuelidate'
Vue.use(Vuelidate)

import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'

// Install BootstrapVue
Vue.use(BootstrapVue)
// Optionally install the BootstrapVue icon components plugin
Vue.use(IconsPlugin)

import VueRouter from 'vue-router'
Vue.use(VueRouter)

import JsonExcel from "vue-json-excel"

Vue.component("downloadExcel", JsonExcel)

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('step1', require('./components/views/records/Step1FormComponent.vue').default);
Vue.component('step2', require('./components/views/records/Step2FormComponent.vue').default);
Vue.component('step3', require('./components/views/records/Step3FormComponent.vue').default);
Vue.component('step4', require('./components/views/records/Step4FormComponent.vue').default);
Vue.component('step5', require('./components/views/records/Step5FormComponent.vue').default);
Vue.component('search-form', require('./components/views/records/SearchFormComponent.vue').default);
Vue.component('tab-form', require('./components/views/records/TabFormComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    el: '#app',
    // vuetify: new Vuetify(),
});
