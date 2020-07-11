import Vue from 'vue'
import Vuetify from 'vuetify'

import { getLocale } from '~/tools/helpers'
import locales from '~/i18n/vuetify'
import 'material-design-icons-iconfont/dist/material-design-icons.css'

Vue.use(Vuetify, {
    lang: {
        locales: locales,
        current: getLocale()
    },
    theme: {
        primary: '#1976D2',
        secondary: '#202124', // '#424242',
        accent: '#82B1FF',
        error: '#FF5252',
        info: '#2196F3',
        success: '#4CAF50',
        warning: '#FFC107',
        footer: '#f1f3f4'

        // primary: '#00adb5', // '#A6CB45', // '#66BB6A',
        // secondary: '#393e46', // '#FAFAFA', #e1f6f4
        // accent: '#a4f9f1', // '#e1f6f4', // '#388E3C',
        // error: '#f44336',
        // warning: '#FFB74D',
        // info: '#2196f3',
        // success: '#4caf50'
    }
})