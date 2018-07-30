require('./bootstrap')
import Vue from 'vue'
import Router from 'vue-router'
import store from './store'
import App from './App.vue'
import Snotify from 'vue-snotify'
import SignIn from './components/signInComponent.vue'
import SignUp from './components/signUpComponent.vue'
import Nearby from './components/nearbyShopsComponent.vue'
import Preferred from './components/preferredShopsComponent.vue'
import './utils/interceptor'


Vue.use(Router)
Vue.use(Snotify)
Vue.config.productionTip = false
const PageNotFound = {
    template: `
  <div class="col-md-10 offset-md-1">
            <div class="alert alert-danger text-center" role="alert">
                <h1>Page Not Found</h1>
            </div>
        </div>`
}
const router = new Router({
    mode: 'history',
    props: true,
    routes: [{
            path: '/',
            redirect: {
                name: 'SignIn'
            }
        },
        {
            path: '/sign-in',
            name: 'SignIn',
            component: SignIn,
            meta: {
                requiresAuth: false
            }
        },
        {
            path: '/sign-up',
            name: 'SignUp',
            component: SignUp,
            meta: {
                requiresAuth: false
            }
        },
        {
            path: '/nearby-shops',
            name: 'Nearby',
            component: Nearby,
            meta: {
                requiresAuth: true
            }
        }, {
            path: '/preferred-shops',
            name: 'Preferred',
            component: Preferred,
            meta: {
                requiresAuth: true
            }
        },
        {
            path: '*',
            name: 'Page404',
            component: PageNotFound
        }
    ],
    scrollBehavior(to, from, savedPosition) {
        if (savedPosition) {
            return savedPosition
        } else {
            return {
                x: 0,
                y: 0
            }
        }
    }
})
router.beforeEach((to, from, next) => {
    if (to.matched.some(route => route.meta.requiresAuth) && !store.getters.isLoggedIn) {
        next({
            name: 'SignIn'
        })
        return
    }
    if ((to.path === '/sign-in' || to.path === '/sign-up') && store.getters.isLoggedIn) {
        next({
            name: 'Nearby'
        })
        return
    }
    next()
})
const app = new Vue({
    el: '#app',
    router,
    store,
    components: {
        App
    },
    template: '<App/>'
})
