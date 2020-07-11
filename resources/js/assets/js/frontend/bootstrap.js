window._ = require('lodash');

try {
    window.$ = window.jQuery = require('jquery');

    require('bootstrap-sass');
} catch (e) {
}


require('es6-promise').polyfill();

window.Vue = require('vue');

window.axios = require('axios');

window.debounce = require('debounce');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');

if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

require('jquery-ui/ui/widgets/datepicker');

let lang = $('html').attr('lang'),
    datepicker_lang = lang === 'en' ? 'en-GB' : lang;

require('jquery-ui/ui/i18n/datepicker-' + datepicker_lang);

$.datepicker.setDefaults($.datepicker.regional[datepicker_lang]);

require('timepicker');

window.moment = require('moment');

window.moment.locale(lang);

require('intl-tel-input/build/js/intlTelInput');
require('intl-tel-input/build/js/utils');

require('geocomplete');

require('select2');

require('lightgallery');

window.iziToast = require('izitoast');

window.bootbox = require('bootbox');

require('slick-carousel');

require('jquery-colorbox');

require('../../../../node_modules/semantic-ui/dist/components/sticky.min.js');

require('jquery-plainoverlay');

window.MobileDetect = require('mobile-detect');

require('fuckadblock');

import VModal from 'vue-js-modal';

Vue.use(VModal);

// Folosesti in vue v-click-outside="functie" pentru a executa o functie cand se face click in afara unui element
Vue.directive('click-outside', {
    bind: function (el, binding, vnode) {
        el.clickOutsideEvent = function (event) {

            if (!(el == event.target || el.contains(event.target))) {

                vnode.context[binding.expression](event);
            }
        };
        document.body.addEventListener('click', el.clickOutsideEvent)
    },
    unbind: function (el) {
        document.body.removeEventListener('click', el.clickOutsideEvent)
    },
});
