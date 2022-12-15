import VueRouter from 'vue-router';

const router = new VueRouter({
    mode: 'history',
    routes: [{
            path: '/',
            name: 'projects',
            component: require('./components/pages/projects/index.vue').default
        },
        {
            path: '/home',
            name: 'home',
            component: require('./components/pages/Home.vue').default,
        },
        {
            path: '/projects/integration/:slug',
            name: 'ShowIntegration',
            props: true,
            component: require('./components/pages/projects/show.vue').default
        },
        {
            path:'/*',
            component: require('./components/pages/projects/index.vue').default

        }
    ]
});


export default router