import Vue from 'vue'
import Router from 'vue-router'
import { interopDefault } from './utils'
import scrollBehavior from './router.scrollBehavior.js'

const _7a408ef0 = () => interopDefault(import('..\\resources\\client\\pages\\inspire.vue' /* webpackChunkName: "pages_inspire" */))
const _d353e8ea = () => interopDefault(import('..\\resources\\client\\pages\\auth\\forgot-password.vue' /* webpackChunkName: "pages_auth_forgot-password" */))
const _71d647e8 = () => interopDefault(import('..\\resources\\client\\pages\\auth\\signin.vue' /* webpackChunkName: "pages_auth_signin" */))
const _48a99afc = () => interopDefault(import('..\\resources\\client\\pages\\auth\\signup.vue' /* webpackChunkName: "pages_auth_signup" */))
const _20efa59c = () => interopDefault(import('..\\resources\\client\\pages\\auth\\verify-email.vue' /* webpackChunkName: "pages_auth_verify-email" */))
const _74f04b3a = () => interopDefault(import('..\\resources\\client\\pages\\profile\\settings.vue' /* webpackChunkName: "pages_profile_settings" */))
const _bee857f0 = () => interopDefault(import('..\\resources\\client\\pages\\profile\\settings\\index.vue' /* webpackChunkName: "pages_profile_settings_index" */))
const _d2a94cde = () => interopDefault(import('..\\resources\\client\\pages\\profile\\settings\\emails.vue' /* webpackChunkName: "pages_profile_settings_emails" */))
const _adfa71c4 = () => interopDefault(import('..\\resources\\client\\pages\\profile\\settings\\notifications.vue' /* webpackChunkName: "pages_profile_settings_notifications" */))
const _00fb2955 = () => interopDefault(import('..\\resources\\client\\pages\\profile\\settings\\password.vue' /* webpackChunkName: "pages_profile_settings_password" */))
const _494a8a02 = () => interopDefault(import('..\\resources\\client\\pages\\profile\\settings\\phones.vue' /* webpackChunkName: "pages_profile_settings_phones" */))
const _1d00c6a6 = () => interopDefault(import('..\\resources\\client\\pages\\profile\\settings\\soc-accounts.vue' /* webpackChunkName: "pages_profile_settings_soc-accounts" */))
const _50355e86 = () => interopDefault(import('..\\resources\\client\\pages\\auth\\reset-password\\_token.vue' /* webpackChunkName: "pages_auth_reset-password__token" */))
const _3fcf1f5a = () => interopDefault(import('..\\resources\\client\\pages\\auth\\reset-password\\_token\\_email.vue' /* webpackChunkName: "pages_auth_reset-password__token__email" */))
const _f72fc290 = () => interopDefault(import('..\\resources\\client\\pages\\auth\\socialite\\_providerName.vue' /* webpackChunkName: "pages_auth_socialite__providerName" */))
const _1133f7c6 = () => interopDefault(import('..\\resources\\client\\pages\\profile\\_nickname.vue' /* webpackChunkName: "pages_profile__nickname" */))
const _46b88000 = () => interopDefault(import('..\\resources\\client\\pages\\index.vue' /* webpackChunkName: "pages_index" */))

// TODO: remove in Nuxt 3
const emptyFn = () => {}
const originalPush = Router.prototype.push
Router.prototype.push = function push (location, onComplete = emptyFn, onAbort) {
  return originalPush.call(this, location, onComplete, onAbort)
}

Vue.use(Router)

export const routerOptions = {
  mode: 'history',
  base: decodeURI('/'),
  linkActiveClass: 'nuxt-link-active',
  linkExactActiveClass: 'nuxt-link-exact-active',
  scrollBehavior,

  routes: [{
    path: "/inspire",
    component: _7a408ef0,
    name: "inspire"
  }, {
    path: "/auth/forgot-password",
    component: _d353e8ea,
    name: "auth-forgot-password"
  }, {
    path: "/auth/signin",
    component: _71d647e8,
    name: "auth-signin"
  }, {
    path: "/auth/signup",
    component: _48a99afc,
    name: "auth-signup"
  }, {
    path: "/auth/verify-email",
    component: _20efa59c,
    name: "auth-verify-email"
  }, {
    path: "/profile/settings",
    component: _74f04b3a,
    children: [{
      path: "",
      component: _bee857f0,
      name: "profile-settings"
    }, {
      path: "emails",
      component: _d2a94cde,
      name: "profile-settings-emails"
    }, {
      path: "notifications",
      component: _adfa71c4,
      name: "profile-settings-notifications"
    }, {
      path: "password",
      component: _00fb2955,
      name: "profile-settings-password"
    }, {
      path: "phones",
      component: _494a8a02,
      name: "profile-settings-phones"
    }, {
      path: "soc-accounts",
      component: _1d00c6a6,
      name: "profile-settings-soc-accounts"
    }]
  }, {
    path: "/auth/reset-password/:token?",
    component: _50355e86,
    name: "auth-reset-password-token",
    children: [{
      path: ":email?",
      component: _3fcf1f5a,
      name: "auth-reset-password-token-email"
    }]
  }, {
    path: "/auth/socialite/:providerName?",
    component: _f72fc290,
    name: "auth-socialite-providerName"
  }, {
    path: "/profile/:nickname?",
    component: _1133f7c6,
    name: "profile-nickname"
  }, {
    path: "/",
    component: _46b88000,
    name: "index"
  }],

  fallback: false
}

export function createRouter () {
  return new Router(routerOptions)
}
