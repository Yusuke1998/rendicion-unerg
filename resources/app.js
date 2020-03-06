import App from './App.vue';
import '../node_modules/vuetify/dist/vuetify.min.css';
import '@mdi/font/css/materialdesignicons.css'
import Vuetify from 'vuetify'
import swal from 'sweetalert';
import axios from 'axios';
import VueAxios from 'vue-axios';
import VueRouter from 'vue-router';
import router from './router.js';
import eventBus from './plugins/event-bus.js';
import VueAlertify from 'vue-alertify';
require('./bootstrap');
window.Vue = require('vue');
Vue.use(Vuetify);
Vue.use(eventBus);
Vue.use(VueAlertify,{
    notifier: {
        delay: 5,
        position: 'top-right',
        closeButton: true,
    }
});

new Vue({
    vuetify : new Vuetify({
        icons: {
            iconfont: 'mdi',
        }
    }),
    router,
    methods:{
    	loading(name, content, time = 3000){
            swal({
                title:name,
                text:content,
                button:{
                    text: "Ok!",
                    closeModal: false,
                },
                icon:'/spin.gif',
                closeOnClickOutside: false,
                timer: time
            })
        }
    },
    render: h=>h(App)
}).$mount('#app');