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
import notFound from './views/Components/404'

Vue.use(VueRouter);
Vue.use(VueMoment);
Vue.use(Notifications, { velocity });

const routes = [
    { path: '/', component: Index, name: 'home', meta: {title: 'Uppuru'} },
    { path: '/last/', component: LastFiles, name: 'last', meta: {title: 'Last files'} },
    { path: '/file/:id', component: ViewFile, name: 'view', meta: {title: 'View file'}  },
    { path: '/404', component: notFound, name: '404', meta: {title: 'Page not found'}  }
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
    baseURL: '/api'
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

new Vue({
    router,
    render: h => h(App)
}).$mount('#app');
