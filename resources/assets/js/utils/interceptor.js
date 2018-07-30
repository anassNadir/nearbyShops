import axios from 'axios'
import store from '../store'


axios.defaults.baseURL = 'http://localhost:8000/api/'
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.interceptors.request.use(
  config => {
    if (store.state.token !== null) {
      config.headers.common['Authorization'] = `Bearer ${store.state.token}`
    }

    return config
  },
  error => { return Promise.reject(error)}
)

let fetchedToken = false
let subscribers = []

const fetchToken = (access_token) => {
  subscribers = subscribers.filter(callback => callback(access_token))
}

const addSubscriber = (callback) => {
  subscribers.push(callback)
}

axios.interceptors.response.use((response) => {
  return response
}, (error) => {
  const { config, response: { status } } = error
  const originalRequest = config

  if (status === 401) {
    if (!fetchedToken) {
      fetchedToken = true
        store.dispatch('refreshToken').then((response) => {
        axios.defaults.headers.common['Authorization'] = `Bearer ${response.token}`
        fetchToken(response.token)
        fetchedToken = false
      }).catch((error) => {
        store.commit('logoutUser')
        return Promise.reject(error)
      })
    }
    const retryOriginalRequest = new Promise((resolve) => {
      addSubscriber(access_token => {
        originalRequest.headers.Authorization = 'Bearer ' + access_token
        resolve(axios(originalRequest))
      })
    })
    return retryOriginalRequest
  }
  return Promise.reject(error)
})