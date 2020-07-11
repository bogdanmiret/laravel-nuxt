/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import vuetify from '../../src/plugins/vuetify';

import 'vuetify/dist/vuetify.min.css';

require('./bootstrap');

window.Vue = require('vue');

Vue.use(vuetify);

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

// Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('layout-app', require('./components/LayoutApp.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

const app = new Vue({
    vuetify,
    data: () => ({
        drawer: null,
        items: [
            { icon: 'mdi-contacts', text: 'Contacts' },
            { icon: 'mdi-history', text: 'Frequently contacted' },
            { icon: 'mdi-content-copy', text: 'Duplicates' },
            {
                icon: 'mdi-chevron-up',
                'icon-alt': 'mdi-chevron-down',
                text: 'Labels',
                model: true,
                children: [
                    { icon: 'mdi-plus', text: 'Create label' },
                ],
            },
            {
                icon: 'mdi-chevron-up',
                'icon-alt': 'mdi-chevron-down',
                text: 'More',
                model: false,
                children: [
                    { text: 'Import' },
                    { text: 'Export' },
                    { text: 'Print' },
                    { text: 'Undo changes' },
                    { text: 'Other contacts' },
                ],
            },
            { icon: 'mdi-settings', text: 'Settings' },
            { icon: 'mdi-message', text: 'Send feedback' },
            { icon: 'mdi-help-circle', text: 'Help' },
            { icon: 'mdi-cellphone-link', text: 'App downloads' },
            { icon: 'mdi-keyboard', text: 'Go to the old version' },
        ],
    }),
    created() {
        this.$vuetify.theme.dark = true
    },
    // render: h => h(App)
}).$mount('#app');