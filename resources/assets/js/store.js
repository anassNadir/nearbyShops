import Vue from 'vue'
import Vuex from 'vuex'
import axios from 'axios'
Vue.use(Vuex)

const WriteCookieToken = (token) => {
    var now = new Date()
    now.setHours(now.getHours() + 24)
    document.cookie = 'auth_token = ' + token +
        '; expires = ' + now.toUTCString() + ';path=/'
}
const killCookieToken = () => {
    document.cookie = 'auth_token =; expires=Thu, 01 Jan 1970 00:00:01 GMT;path=/'
}

const getCookieToken = () => {
    var pair = document.cookie.match(new RegExp('auth_token=([^;]+)'))
    return pair ? pair[1] : null
}

export default new Vuex.Store({
    state: {
        token: getCookieToken() || '',
        allNearbyShops: [],
        allPreferredShops: [],
        isLoading: false
    },
    mutations: {
        authSuccess(state, response) {
            state.token = response.token
            WriteCookieToken(response.token)
        },
        updateLoader(state, payload) {
            state.isLoading = payload
        },
        logoutUser(state) {
            state.token = ''
            killCookieToken()
        },
        fillNearbyShops(state, shops) {
            state.allNearbyShops = shops
            state.isLoading = false
        },
        fillPreferredShops(state, shops) {
            state.allPreferredShops = shops
            state.isLoading = false
        }
    },
    getters: {
        nearbyShops: state => {
            return state.allNearbyShops
        },
        preferredShops: state => {
            return state.allPreferredShops
        },
        isLoggedIn: state => !!state.token
    },
    actions: {
        getNearbyShops({
            commit
        }, position) {
            return new Promise((resolve, reject) => {
                axios.get('shops/nearbyShops', {
                        params: {
                            longitude: position && position.longitude,
                            latitude: position && position.latitude
                        }
                    })
                    .then(res => {
                        commit('fillNearbyShops', res.data.nearbyShops)
                        resolve(res)
                    })
                    .catch(err => {
                        reject(err)
                    })
            })

        },
        getPreferredShops({
            commit
        }) {
            commit('updateLoader', true)
            return new Promise((resolve, reject) => {
                axios.get('shops/preferredShops')
                    .then(res => {
                        commit('fillPreferredShops', res.data.preferredShops)
                        resolve(res)
                    })
                    .catch(err => {
                        reject(err)
                    })
            })
        },
        likeShop({}, shop) {
            return new Promise((resolve, reject) => {
                axios.post('shops/likeShop', shop)
                    .then(res => {
                        resolve(res)
                    })
                    .catch(err => {
                        reject(err)
                    })
            })
        },
        dislikeShop({}, shop) {
            return new Promise((resolve, reject) => {
                axios.post('shops/dislikeShop', shop)
                    .then(res => {
                        resolve(res)
                    })
                    .catch(err => {
                        reject(err)
                    })
            })
        },
        removeShop({}, shop) {
            return new Promise((resolve, reject) => {
                axios.post('shops/separateShop', shop)
                    .then(res => {
                        resolve(res)
                    })
                    .catch(err => {
                        reject(err)
                    })
            })
        },
        authenticate({
            commit
        }, form) {
            return new Promise((resolve, reject) => {
                axios.post('user/signIn', form)
                    .then(res => {
                        commit('authSuccess', res.data)
                        resolve()
                    })
                    .catch(err => {
                        reject(err.response.data)
                        killCookieToken()
                    })
            })
        },
        register({
            commit
        }, form) {
            return new Promise((resolve, reject) => {
                axios.post('user/signUp', form)
                    .then(res => {
                        commit('authSuccess', res.data)
                        resolve()
                    })
                    .catch(err => {
                        reject(err.response.data)
                    })
            })
        },
        refreshToken: ({
            commit
        }) => {
            return new Promise((resolve, reject) => {
                axios.get('user/auth/refreshToken')
                    .then(res => {
                        commit('authSuccess', res.data)
                        resolve(res.data)
                    })
                    .catch(err => {
                        reject(err)
                    })
            })
        },
        logout: ({
            commit
        }) => {
            return new Promise((resolve, reject) => {
                axios.post('user/logout')
                    .then(res => {
                        commit('logoutUser')
                        resolve(res)
                    })
                    .catch(err => {
                        reject(err)
                    })
            })
        }
    }
})
