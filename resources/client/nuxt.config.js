import colors from 'vuetify/es5/util/colors';

import { join } from 'path';
import { copySync, removeSync } from 'fs-extra';

const universalModeConfig = {
    mode: 'universal',
    /*
     ** Headers of the page
     */
    head: {
        titleTemplate: '%s - ' + process.env.npm_package_name,
        title: process.env.npm_package_name || '',
        meta: [
            { charset: 'utf-8' },
            { name: 'viewport', content: 'width=device-width, initial-scale=1' },
            {
                hid: 'description',
                name: 'description',
                content: process.env.npm_package_description || ''
            }
        ],
        link: [{ rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }]
    },
    /*
     ** Global CSS
     */
    css: [],

    loading: {
        color: '#1976D2',
        height: '4px'
    },

    loadingIndicator: '~/layouts/loading.html',

    auth: {
        vuex: {
            namespace: 'nuxtAuth'
        },
        strategies: {
            local: {
                endpoints: {
                    login: { url: '/api/auth/login', method: 'post', propertyName: 'access_token' },
                    logout: { url: '/api/auth/logout', method: 'post' },
                    user: { url: '/api/auth/user', method: 'get', propertyName: 'user' }
                },
                tokenRequired: true,
                tokenType: 'Bearer',
                // autoFetchUser: false
            }
        },
        redirect: {
            login: '/auth/signin',
            callback: '/auth/signin',
            // home: false,
            logout: '/' // возможно логичней не перенаправлять(false)
                // user: После входа идет перенаправление на прошлый роут
        }
    },

    router: {
        middleware: 'global'
    },
    /*
     ** Plugins to load before mounting the App
     */
    plugins: [
        { src: '~/plugins/vuetify' },
        { src: '~/plugins/vee-validate' },
        { src: '~/plugins/fontawesome' },
        { src: '~/plugins/vue-notifications', ssr: false },
        // { src: '~/plugins/vuex-shared-mutations.js', ssr: false },
        // { src: '~/plugins/ga.js', ssr: false },
        // { src: '~/plugins/dayjs.js' },
        { src: '~/plugins/filters.js' }
    ],
    /*
     ** Nuxt.js dev-modules
     */
    buildModules: [
        // Doc: https://github.com/nuxt-community/eslint-module
        // '@nuxtjs/eslint-module',
        '@nuxtjs/vuetify'
    ],
    /*
     ** Nuxt.js modules
     */
    modules: [
        // Doc: https://axios.nuxtjs.org/usage
        '@nuxtjs/axios',
        '@nuxtjs/auth',
        // '@nuxtjs/pwa',
        // Doc: https://github.com/nuxt-community/dotenv-module
        ['@nuxtjs/dotenv', { path: './' }],
        'cookie-universal-nuxt'
    ],
    /*
     ** Axios module configuration
     ** See https://axios.nuxtjs.org/options
     */
    axios: {
        // browserBaseURL: process.env.APP_URL,
        browserBaseURL: 'http://127.0.0.1:9000',
        // baseURL: process.env.APP_URL // api url 'http://127.0.0.1:9000'
        baseURL: 'http://127.0.0.1:9000' // api url 'http://127.0.0.1:9000'
    },
    // vue: {
    //     config: {
    //         productionTip: true,
    //         devtools: true
    //     }
    // },
    /*
     ** vuetify module configuration
     ** https://github.com/nuxt-community/vuetify-module
     */
    vuetify: {
        customVariables: ['~/assets/variables.scss'],
        theme: {
            dark: true
        }
    },
    /*
     ** Build configuration
     */
    build: {
        // publicPath: 'nuxt',
        /*
         ** You can extend webpack config here
         */
        extend(config, ctx) {
            config.output.publicPath = '/nuxt/_nuxt/';
        }
    },
    srcDir: 'resources/client',
    generate: {
        dir: 'public/nuxt',
        devtools: true
    },
    // buildDir: 'public/nuxt',
    hooks: {
        generate: {
            done(generator) {
                // Copy dist files to public/_nuxt
                if (generator.nuxt.options.dev === false && generator.nuxt.options.mode === 'spa') {
                    // const publicDir = join(generator.nuxt.options.rootDir, 'public', '_nuxt')
                    // removeSync(publicDir)
                    // copySync(join(generator.nuxt.options.generate.dir, '_nuxt'), publicDir)
                    // copySync(join(generator.nuxt.options.generate.dir, '200.html'), join(publicDir, 'index.html'))
                    // removeSync(generator.nuxt.options.generate.dir)
                }
            }
        }
    }
}

export default universalModeConfig;
