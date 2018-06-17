// The Vue build version to load with the `import` command
// (runtime-only or standalone) has been set in webpack.base.conf with an alias.
import Vue from 'vue'
import FastClick from 'fastclick'
import VueRouter from 'vue-router'
import App from './App'

import VueResource from "vue-resource"
import VueCookie from "vue-cookie"
import  { ToastPlugin,LoadingPlugin } from 'vux'


import router from './router/index'

Vue.use(VueRouter);
Vue.use(VueResource);
Vue.use(VueCookie);
// 默认参数
Vue.use(ToastPlugin, {position: 'top'});
Vue.use(LoadingPlugin);


FastClick.attach(document.body)

Vue.config.productionTip = false

/* eslint-disable no-new */
new Vue({
  router,
  render: h => h(App)
}).$mount('#app-box')
