import Vue from 'vue'
import VueRouter from 'vue-router'
import VueMoment from 'vue-moment'
import Notifications from 'vue-notification'
import axios from 'axios'

import velocity from 'velocity-animate'
import "bootstrap/dist/css/bootstrap.min.css"
import "vue-material-design-icons/styles.css"
import "./views/assets/css/main.css"


import App from './views/App'
import Index from './views/Index'
import LastFiles from './views/LastFiles'
import ViewFile from './views/ViewFile'
import Error from './views/Error'

Vue.use(VueRouter);
Vue.use(VueMoment);
Vue.use(Notifications, { velocity });

const api = process.env.VUE_API_URI;

const routes = [
    { path: '/', component: Index, name: 'home', meta: {title: 'Uppuru'} },
    { path: '/last/', component: LastFiles, name: 'last', meta: {title: 'Last files'} },
    { path: '/file/:id', component: ViewFile, name: 'view', meta: {title: 'View file'}, props: true },
    { path: '/error', component: Error, name: 'error', meta: {title: 'Not found'}, props: true },
];


const router = new VueRouter({
    mode: 'history',
    routes
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title;
    if (!to.matched.length) {
        next('/notFound');
    }
    next()
});

Vue.prototype.$http = axios.create({
    baseURL: api
});

Vue.prototype.$http.interceptors.response.use((response) => {
    return response;
}, (error) => {
    let data = error.response.data;
    if (data) {
        router.push({
            name: 'error',
            params: { error: data }
        });
    }
    return error;
});

Vue.mixin({
    methods: {
        sendError(text) {
            this.$notify({
                group: 'errors',
                title: 'Error',
                text: text,
                duration: 5000
            });
        }
    }
});

Vue.filter('bitsConvert', function (value) {
        if(value > 1048576) {
            return Math.round(value / 1048576) + ' MBits';
        }
        return Math.round(value / 1024) + ' KBits';
});


new Vue({
    router,
    render: h => h(App)
}).$mount('#app');
