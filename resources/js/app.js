import Vue from 'vue'
import Index from './components/Index'
import { BootstrapVue, IconsPlugin } from 'bootstrap-vue'
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'
import AOS from 'aos'
import 'aos/dist/aos.css'

Vue.use(BootstrapVue)
Vue.use(IconsPlugin)

require('./bootstrap');

const app = new Vue({
    el: '#app',
    components: {
        "index":  Index
    },
    mounted() {
        AOS.init()
    }
});
