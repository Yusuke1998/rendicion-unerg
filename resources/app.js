import App from './App.vue';
import swal from 'sweetalert';
import axios from 'axios';
import VueAxios from 'vue-axios';
import eventBus from './plugins/event-bus.js';
require('./bootstrap');
window.Vue = require('vue');



// DECLARANDO COMPONENTES
// FIN COMPONENTES

/* PLUGINS */
Vue.use(eventBus);
/* FIN DE PLUGINS */

new Vue({
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

// new Vue(App).$mount('#app');