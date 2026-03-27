import { createRouter, createWebHistory } from 'vue-router';
import landingPage from '../views/landingPage.vue';
import login from '../views/auth/login.vue';
import register from '../views/auth/register.vue';
import general from '../views/general.vue';
import Verification from '@/views/auth/verification.vue';

const router = createRouter({
    history: createWebHistory(),
    routes: [
        {
            path: '/',
            name: 'landing',
            component: landingPage
        },
        {
            path: '/login',
            name: 'login',
            component: login
        },
        {
            path: '/register',
            name: 'register',
            component: register
        },
        {
            path: '/verificar-email',
            name: 'verificar',
            component: Verification
        },
        {
            path: '/general',
            name: 'general',
            component: general
        }
    ]
})

export default router;