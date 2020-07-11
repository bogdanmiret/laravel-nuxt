import Vue from 'vue'
import Cookie from 'js-cookie'
// import { msg as msgForThisFile } from '~/tools/helpers'

const getFuncForShowMsg = (mainType) => (title, message, type, timeout) => {
    // Vue.prototype.$notify не будет доступен если просто записать в экспортированную переменную которую
    const notify = Vue.prototype.$notify
    notify(title, message, mainType, timeout)
}

// массив функций обозначающих тип сообщения
export const msg = {
    success: getFuncForShowMsg('success'),
    info: getFuncForShowMsg('info'),
    warn: getFuncForShowMsg('warn'),
    error: getFuncForShowMsg('error')
}

// возвращает путь флага
export function getFlag(flagName) {
    try {
        return require(`~/assets/flags/${flagName.toLowerCase()}.svg`)
    } catch (e) {
        return require('~/assets/flags/undefined.svg')
    }
}

// возвращает локаль
export function getLocale() {
    if (Cookie.get('locale')) {
        return Cookie.get('locale')
    }

    return process.env.localeDefault
}

// ставит локаль
export function setLocale(locale) {
    Cookie.set('locale', locale)
}

export const showServerError = ({ data }) => {
    const { errors } = data
    let message = ''

    if (errors) {
        for (let field in errors) {
            if (errors.hasOwnProperty(field)) {
                for (let error of errors[field]) {
                    message += `${error}<br>`
                }
            }
        }
    } else {
        message = data.message
    }

    msg.error(message)
}


export const request = async(closure, { throwErr = false, ifCatchThenReturn = false } = {}) => {
    try {
        const result = await closure()

        return result || true
    } catch (e) {
        const { response } = e

        if (response) {
            showServerError(response)
        } else {
            console.error(e)
        }

        if (throwErr) {
            throw e
        } else {
            return ifCatchThenReturn
                // return e
        }
    }
}