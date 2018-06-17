import Vue from 'vue'
import Vuex from 'vuex'
//import routeLoading from './modules/route'
import page from './modules/page'

Vue.use(Vuex)

const store = new Vuex.Store({
    strict: process.env.NODE_ENV !== 'production',
    modules: {
        page
        //routeLoading
    }
})

export default store
